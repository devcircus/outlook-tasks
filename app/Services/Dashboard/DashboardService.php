<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Services\Email\ListEmailsService;
use App\Services\Cache\CacheForeverService;
use App\Services\Task\ListGroupedTasksService;
use PerfectOblivion\Services\Traits\SelfCallingService;

class DashboardService
{
    use SelfCallingService;

    /**
     * Handle the call to the service.
     *
     * @param  string  $category
     * @param  string  $calendar
     * @param  \App\Models\User  $user
     */
    public function run(string $category = 'all', string $calendar = 'all', User $user): array
    {
        return [
            'filters' => ['category' => $category, 'calendar' => $calendar],
            'tasks' => ListGroupedTasksService::call($category, $calendar, $user),
            'email' => ListEmailsService::call($user),
        ];
    }
}
