<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\User;
use PerfectOblivion\Services\Traits\SelfCallingService;

class ListTasksService
{
    use SelfCallingService;

    /** @var \App\Models\Tasks */
    private $tasks;

    /**
     * Construct a new ListTasksService.
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
        return $user->tasks()->incomplete()->orderByColumn('due_date', 'asc')->get();
    }
}
