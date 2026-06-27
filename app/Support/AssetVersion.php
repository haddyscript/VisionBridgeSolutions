<?php

namespace App\Support;

class AssetVersion
{
    /**
     * Build a public asset URL with a cache-busting query string based on the
     * file's last-modified time, so browsers fetch the new version on a
     * normal reload instead of serving a stale cached copy.
     */
    public static function url(string $path): string
    {
        $absolute = public_path($path);

        $version = file_exists($absolute) ? filemtime($absolute) : time();

        return asset($path).'?v='.$version;
    }
}
