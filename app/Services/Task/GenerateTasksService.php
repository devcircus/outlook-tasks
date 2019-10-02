<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\Total;
use App\Events\TasksGenerated;
use App\Services\Cache\CacheForgetService;
use Illuminate\Database\Eloquent\Collection;
use PerfectOblivion\Services\Traits\SelfCallingService;

class GenerateTasksService
{
    use SelfCallingService;

    /** @var \App\Models\Task */
    private $tasks;

    /** @var \App\Models\Total */
    private $totals;

    /**
     * Construct a new GenerateTaskService.
     *
     * @param  \App\Models\Task  $tasks
     * @param  \App\Models\Total  $totals
     */
    public function __construct(Task $tasks, Total $totals)
    {
        $this->tasks = $tasks;
        $this->totals = $totals;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $emails
     */
    public function run(Collection $emails): void
    {
        $user = $emails->first()->user;

        CacheForgetService::call('quantities', $user->id);
        $this->totals->forUser($user)->delete();

        $emails->each(function ($email) {
            GenerateTaskFromEmailService::call($email);
        });

        TasksGenerated::broadcast();
    }
}
