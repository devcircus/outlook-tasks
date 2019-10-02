<?php

namespace App\Services\Cache;

use Closure;
use PerfectOblivion\Services\Traits\SelfCallingService;

class CacheForeverService extends CacheService
{
    use SelfCallingService;

    /**
     * Handle the call to the service.
     *
     * @param  string  $type
     * @param  \Closure  $callback
     * @param  string|null  $rawSuffix
     *
     * @return mixed
     */
    public function run(string $type, Closure $callback, ?string $rawSuffix = null)
    {
        return $this->forever($this->getCacheKey($type, $rawSuffix), $callback);
    }
}
