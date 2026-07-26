<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class AssetVersion
{
    /**
     * Build a public asset URL with a cache-busting query string based on the
     * file's last-modified time, so browsers fetch the new version on a
     * normal reload instead of serving a stale cached copy.
     *
     * The mtime lookup is cached forever per path rather than stat'd on every
     * request — it can't go stale in a way that matters, since the deploy
     * pipeline's own cache-clear step (see the /deployer route) invalidates
     * this the moment a new file version could actually exist.
     */
    public static function url(string $path): string
    {
        $version = Cache::rememberForever("assetv:{$path}", function () use ($path) {
            $absolute = public_path($path);

            return file_exists($absolute) ? filemtime($absolute) : time();
        });

        return asset($path).'?v='.$version;
    }
}
