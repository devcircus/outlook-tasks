<?php

namespace App\Services\Task;

use PerfectOblivion\Services\Traits\SelfCallingService;
use App\Models\Task;
use App\Models\User;

class RestoreTaskService
{
    use SelfCallingService;

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\Task  $task
     * @param  \App\Models\User  $user
     *
     * @return \App\Models\Task
     */
    public function run(Task $task, User $user)
    {
        return $task->restoreTask($user->id);
    }
}
