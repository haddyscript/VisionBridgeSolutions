<?php

namespace App\Console\Commands;

use App\Models\SiteVisit;
use App\Support\IpCountry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * Downloads DB-IP's free "IP to Country Lite" database (CC BY 4.0 —
 * attribution shown on the Website Visitors report) and loads it into
 * `ip_country_ranges`. Scheduled daily but only re-downloads once the data
 * is over 30 days old (DB-IP publishes monthly), or with --force.
 */
class UpdateIpCountryDatabase extends Command
{
    protected $signature = 'geoip:update {--force : Re-download even if the data is recent}';

    protected $description = 'Download the free DB-IP country database used to show website visitor countries';

    private const UPDATED_KEY = 'geoip:updated_at';

    public function handle(): int
    {
        $updatedAt = Cache::get(self::UPDATED_KEY);
        $hasData = DB::table('ip_country_ranges')->exists();

        if (! $this->option('force') && $hasData && $updatedAt && now()->diffInDays($updatedAt, true) < 30) {
            $this->info('Country database is up to date (last updated '.$updatedAt->toDateString().').');

            return self::SUCCESS;
        }

        @set_time_limit(900);

        $path = storage_path('app/dbip-country-lite.csv.gz');

        // This month's file appears early in the month — fall back to last month's.
        $downloaded = false;
        foreach ([now(), now()->subMonthNoOverflow()] as $month) {
            $url = 'https://download.db-ip.com/free/dbip-country-lite-'.$month->format('Y-m').'.csv.gz';
            $response = Http::timeout(180)->sink($path)->get($url);

            if ($response->successful()) {
                $this->info("Downloaded {$url}");
                $downloaded = true;
                break;
            }
        }

        if (! $downloaded) {
            @unlink($path);
            $this->error('Could not download the DB-IP country database.');

            return self::FAILURE;
        }

        $gz = gzopen($path, 'rb');
        $count = 0;

        // One transaction: lookups keep using the old data until the new set
        // is fully loaded, and a failed import leaves the old data in place.
        DB::transaction(function () use ($gz, &$count) {
            DB::table('ip_country_ranges')->delete();

            $batch = [];
            while (($row = fgetcsv($gz)) !== false) {
                [$from, $to, $country] = array_pad($row, 3, null);
                $fromBin = @inet_pton((string) $from);
                $toBin = @inet_pton((string) $to);

                // ZZ = unassigned/unknown in DB-IP's data.
                if ($fromBin === false || $toBin === false || ! preg_match('/^[A-Z]{2}$/', (string) $country) || $country === 'ZZ') {
                    continue;
                }

                $batch[] = ['is_v4' => strlen($fromBin) === 4, 'ip_from' => $fromBin, 'ip_to' => $toBin, 'country' => $country];

                if (count($batch) === 2000) {
                    DB::table('ip_country_ranges')->insert($batch);
                    $count += count($batch);
                    $batch = [];
                }
            }

            if ($batch) {
                DB::table('ip_country_ranges')->insert($batch);
                $count += count($batch);
            }
        });

        gzclose($gz);
        @unlink($path);

        if ($count === 0) {
            $this->error('The downloaded file had no usable rows — kept nothing.');

            return self::FAILURE;
        }

        Cache::forever(self::UPDATED_KEY, now());
        $this->info('Loaded '.number_format($count).' IP ranges.');

        $this->backfillVisits();

        return self::SUCCESS;
    }

    /** Fill in the country for already-recorded visits (IPs are kept 90 days). */
    private function backfillVisits(): void
    {
        $filled = 0;

        SiteVisit::whereNull('country')->whereNotNull('ip_address')
            ->select('ip_address')->distinct()->pluck('ip_address')
            ->each(function ($ip) use (&$filled) {
                if ($country = IpCountry::lookup($ip)) {
                    $filled += SiteVisit::whereNull('country')->where('ip_address', $ip)->update(['country' => $country]);
                }
            });

        $this->info('Filled in the country for '.number_format($filled).' earlier visits.');
    }
}
