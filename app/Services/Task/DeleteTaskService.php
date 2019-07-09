<?php

namespace App\Services\Task;

use App\Models\Task;
use PerfectOblivion\Services\Traits\SelfCallingService;

class DeleteTaskService
{
    use SelfCallingService;

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\Task  $task
     *
     * @return \App\Models\Task
     */
    public function run(Task $task)
    {
        return $task->deleteTask();
    }
}
