<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Services\Task\ListTasksService;
use PerfectOblivion\Services\Traits\SelfCallingService;

class IndexService
{
    use SelfCallingService;

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\User  $user
     *
     * @return \Illuminate\Support\Collection
     */
    public function run(User $user)
    {
        return ListTasksService::call($user);
    }
}
