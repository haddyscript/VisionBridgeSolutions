<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Super-admin read-only viewer for storage/logs, so checking errors doesn't
 * need a trip into the Hostinger file manager. Only the tail of the file is
 * read (logs can grow to hundreds of MB); the full file is still downloadable.
 */
class LaravelLogController extends Controller
{
    private const TAIL_BYTES = 2 * 1024 * 1024;

    private const MAX_ENTRIES = 300;

    public const LEVELS = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];

    public function index(Request $request)
    {
        $files = $this->logFiles();
        $file = $this->resolveFile($request->query('file'), $files);

        $level = in_array($request->query('level'), self::LEVELS, true) ? $request->query('level') : null;
        $search = trim((string) $request->query('search'));

        $entries = $file ? $this->parse($this->tail($file)) : collect();
        $levelCounts = $entries->countBy('level');

        $entries = $entries
            ->when($level, fn ($c) => $c->where('level', $level))
            ->when($search !== '', fn ($c) => $c->filter(fn ($e) => Str::contains($e['message'].$e['details'], $search, true)))
            ->reverse()
            ->take(self::MAX_ENTRIES)
            ->values();

        return view('admin.laravel-log.index', [
            'files' => $files,
            'file' => $file,
            'fileSize' => $file ? filesize($file) : 0,
            'truncated' => $file && filesize($file) > self::TAIL_BYTES,
            'entries' => $entries,
            'levelCounts' => $levelCounts,
            'level' => $level,
            'search' => $search,
            'maxEntries' => self::MAX_ENTRIES,
        ]);
    }

    public function download(Request $request)
    {
        $file = $this->resolveFile($request->query('file'), $this->logFiles());

        abort_unless($file, 404);

        return response()->download($file);
    }

    /** Newest first, keyed by basename — the only names a request may pick from. */
    private function logFiles(): array
    {
        $paths = glob(storage_path('logs/*.log')) ?: [];
        usort($paths, fn ($a, $b) => filemtime($b) <=> filemtime($a));

        return collect($paths)->mapWithKeys(fn ($p) => [basename($p) => $p])->all();
    }

    private function resolveFile(?string $name, array $files): ?string
    {
        return $files[$name] ?? (array_values($files)[0] ?? null);
    }

    private function tail(string $path): string
    {
        $size = filesize($path);
        $handle = fopen($path, 'rb');

        if ($size > self::TAIL_BYTES) {
            fseek($handle, $size - self::TAIL_BYTES);
            fgets($handle); // drop the partial first line
        }

        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;
    }

    private function parse(string $content)
    {
        $pattern = '/^\[(\d{4}-\d{2}-\d{2}[ T][^\]]+)\] (\w+)\.(\w+): /m';

        preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

        return collect($matches)->map(function ($match, $i) use ($content, $matches) {
            $start = $match[0][1] + strlen($match[0][0]);
            $end = isset($matches[$i + 1]) ? $matches[$i + 1][0][1] : strlen($content);
            $body = rtrim(substr($content, $start, $end - $start));
            [$message, $details] = array_pad(explode("\n", $body, 2), 2, '');

            return [
                'time' => $match[1][0],
                'env' => $match[2][0],
                'level' => strtolower($match[3][0]),
                'message' => $message,
                'details' => $details,
            ];
        });
    }
}
