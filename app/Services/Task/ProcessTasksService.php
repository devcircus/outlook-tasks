<?php

namespace App\Services\Task;

use App\Models\User;
use App\Models\Email;
use App\Events\NoTasksToGenerate;
use App\Services\Task\GenerateTasksService;
use PerfectOblivion\Services\Traits\SelfCallingService;

class ProcessTasksService
{
    use SelfCallingService;

    /** @var \App\Models\Email */
    private $emails;

    /**
     * Construct a new ProcessTasksService.
     *
     * @param  \App\Models\Email  $emails
     */
    public function __construct(Email $emails)
    {
        $this->emails = $emails;
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
        $emails = $user->emails()->notAssignedToTask()->categoryNotNone()->get();

        if ($emails->count() === 0) {
            return NoTasksToGenerate::broadcast();
        }

        return GenerateTasksService::call($emails);
    }
}
