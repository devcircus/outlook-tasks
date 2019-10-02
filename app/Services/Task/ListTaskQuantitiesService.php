<?php

namespace App\Services\Task;

use App\Models\User;
use App\Models\Total;
use App\Services\Cache\CacheForeverService;
use Illuminate\Support\Collection;
use PerfectOblivion\Services\Traits\SelfCallingService;
use App\Services\Task\GetTotalQuantitiesFromDatabaseService;
use App\Services\Task\GenerateTotalQuantitiesFromTasksService;

class ListTaskQuantitiesService
{
    use SelfCallingService;

    /** @var \App\Models\Total */
    private $totals;

    /**
     * Create a ListTaskQuantitiesService.
     *
     * @param  \App\Models\Total  $totals
     */
    public function __construct(Total $totals)
    {
        $this->totals = $totals;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\User  $user
     */
    public function run(User $user): Collection
    {
        return CacheForeverService::call('quantities', function () use ($user) {
            if ($user->totals()->first()) {
                return $this->getTotalsFromDatabase($user);
            }

            return $this->generateTotalsFromTasks($user);
        }, $user->id);
    }

    /**
     * Get the totals from the totals database table.
     *
     * @param  \App\Models\User  $user
     */
    protected function getTotalsFromDatabase(User $user): Collection
    {
        return GetTotalQuantitiesFromDatabaseService::call($user);
    }

    /**
     * Generate the totals from existing tasks.
     *
     * @param  \App\Models\User  $user
     */
    protected function generateTotalsFromTasks(User $user): Collection
    {
        return GenerateTotalQuantitiesFromTasksService::call($user);
    }
}
