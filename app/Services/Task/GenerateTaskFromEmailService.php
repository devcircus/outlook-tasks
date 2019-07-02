<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\Email;
use PerfectOblivion\Services\Traits\SelfCallingService;

class GenerateTaskFromEmailService
{
    use SelfCallingService;

    /** @var \App\Models\Task */
    private $tasks;

    /**
     * Construct a new GenerateTaskFromEmailService.
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
     * @param  \App\Models\Email  $email
     */
    public function run(Email $email): Task
    {
        return $this->tasks->createFromEmail($email);
    }
}
