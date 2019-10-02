<?php

namespace App\Services\Task;

use App\Models\User;
use App\Models\Email;
use App\Models\Total;
use App\Events\NoTasksToGenerate;
use App\Services\Cache\CacheForgetService;
use App\Services\Task\GenerateTasksService;
use PerfectOblivion\Services\Traits\SelfCallingService;

class ProcessTasksService
{
    use SelfCallingService;

    /** @var \App\Models\Email */
    private $emails;

    /** @var \App\Models\Total */
    private $totals;

    /**
     * Construct a new ProcessTasksService.
     *
     * @param  \App\Models\Email  $emails
     * @param  \App\Models\Total  $totals
     */
    public function __construct(Email $emails, Total $totals)
    {
        $this->emails = $emails;
        $this->totals = $totals;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\User  $user
     *
     * @return bool
     */
    public function run(User $user)
    {
        $emails = $user->emails()->processed()->categorized()->withNoTask()->get();

        if ($emails->count() === 0) {
            return NoTasksToGenerate::broadcast();
        }

        CacheForgetService::call('quantities', $user->id);
        $this->totals->forUser($user)->delete();

        return GenerateTasksService::call($emails);
    }
}
