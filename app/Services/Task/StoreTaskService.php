<?php

namespace App\Services\Task;

use App\Models\User;
use App\Models\Task;
use App\Http\DTO\TaskData;
use PerfectOblivion\Services\Traits\SelfCallingService;

class StoreTaskService
{
    use SelfCallingService;

    /** @var \App\Models\Task */
    private $tasks;

    /**
     * Construct a new StoreTaskService.
     *
     * @param  \App\Models\Task  $tasks
     */
    public function __construct(Task  $tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Http\DTO\TaskData  $task
     * @param  \App\Models\User  $user
     * @param  int|null  $email
     *
     * @return \App\Http\Task
     */
    public function run(TaskData $task, User $user, ?int $email = null)
    {
        return $this->tasks->createTaskForUser($task, $user, $email);
    }
}
