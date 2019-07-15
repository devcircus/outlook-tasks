<?php

namespace App\Services\Task;

use PerfectOblivion\Services\Traits\SelfCallingService;
use App\Models\Task;

class RestoreTaskService
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
        return $task->restoreTask();
    }
}
