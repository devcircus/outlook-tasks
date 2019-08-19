<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\User;
use PerfectOblivion\Services\Traits\SelfCallingService;

class ListTodaysTasksService
{
    use SelfCallingService;

    /** @var \App\Models\Tasks */
    private $tasks;

    /**
     * Construct a new ListTodaysTasksService.
     *
     * @param  \App\Models\Tasks  $tasks
     */
    public function __construct(Task $tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\User  $user
     *
     * @return array
     */
    public function run(User $user)
    {
        return $user->tasks()->withTrashed()->forToday()->orderByColumn('title', 'asc')->get();
    }
}
