<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Services\Task\ListTasksService;
use App\Services\Email\ListEmailsService;
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
            'tasks' => ListTasksService::call($user),
            'emails' => ListEmailsService::call($user),
        ];
    }
}
