<?php

namespace App\Services\Task;

use App\Models\Task;
use PerfectOblivion\Services\Traits\SelfCallingService;

class GenerateTasksService
{
    use SelfCallingService;

    /** @var \App\Models\Task */
    private $tasks;

    /**
     * Construct a new GenerateTaskService.
     *
     * @param  \App\Models\Task  $tasks
     */
    public function __construct(Task $tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * Handle the call to the service.
     *
     * @return mixed
     */
    public function run()
    {

    }
}
