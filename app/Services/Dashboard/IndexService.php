<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Services\Email\ListEmailsService;
use App\Services\Task\ListGroupedTasksService;
use PerfectOblivion\Services\Traits\SelfCallingService;

class IndexService
{
    use SelfCallingService;

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\User  $user
     *
     * @return array
     */
    public function run(User $user)
    {
        return [
            'tasks' => ListGroupedTasksService::call($user),
            'emails' => ListEmailsService::call($user),
        ];
    }
}
