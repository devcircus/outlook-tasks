<?php

namespace App\Services\Cache;

use PerfectOblivion\Services\Traits\SelfCallingService;

class CacheForgetService extends CacheService
{
    use SelfCallingService;

    /**
     * Handle the call to the service.
     *
     * @param  string  $type
     * @param  string|null  $rawSuffix
     */
    public function run(string $type, ?string $rawSuffix = null): bool
    {
        return $this->forget($this->getCacheKey($type, $rawSuffix));
    }
}
