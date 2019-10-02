<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\User;
use PerfectOblivion\Services\Traits\SelfCallingService;

class DeleteTaskService
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
        return $task->deleteTask($user->id);
    }
}
