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
        return [
            'prototype' => $user->tasks()->incomplete()->forCategory('prototype')->orderByColumn('due_date', 'asc')->get(),
            'swatch' => $user->tasks()->incomplete()->forCategory('swatch')->orderByColumn('due_date', 'asc')->get(),
            'vsf' => $user->tasks()->incomplete()->forCategory('vsf')->orderByColumn('due_date', 'asc')->get(),
            'ozone' => $user->tasks()->incomplete()->forCategory('ozone')->orderByColumn('due_date', 'asc')->get(),
            'lettering' => $user->tasks()->incomplete()->forCategory('lettering')->orderByColumn('due_date', 'asc')->get(),
        ];
    }
}
