<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Events\TasksGenerated;
use App\Services\Cache\CacheForgetService;
use Illuminate\Database\Eloquent\Collection;
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
     * @param  \Illuminate\Database\Eloquent\Collection  $emails
     */
    public function run(Collection $emails): void
    {
        CacheForgetService::call('quantities', $emails->first()->user->id);

        $emails->each(function ($email) {
            GenerateTaskFromEmailService::call($email);
        });

        TasksGenerated::broadcast();
    }
}
