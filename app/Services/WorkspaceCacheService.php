<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class WorkspaceCacheService
{
    /**
     * Cache version key.
     */
    private const VERSION_KEY = 'workspace_cache_version';

    /**
     * Get the current cache version number.
     */
    public static function getVersion(): int
    {
        return (int) Cache::get(self::VERSION_KEY, 1);
    }

    /**
     * Invalidate all workspace caches by incrementing the cache version.
     */
    public static function invalidateAll(): void
    {
        if (Cache::has(self::VERSION_KEY)) {
            Cache::increment(self::VERSION_KEY);
        } else {
            Cache::forever(self::VERSION_KEY, 2);
        }
    }
}
