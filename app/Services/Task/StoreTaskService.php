<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\User;
use App\Http\DTO\Task as TaskData;
use PerfectOblivion\Services\Traits\SelfCallingService;
use App\Services\Task\Validation\StoreTaskValidationService;

class StoreTaskService
{
    use SelfCallingService;

    /** @var \App\Models\Task */
    private $tasks;

    /**
     * Construct a new StoreTaskService.
     *
     * @param  \App\Models\Task  $tasks
     * @param  \App\Services\Task\Validation\StoreTaskValidationService  $validator
     */
    public function __construct(Task  $tasks, StoreTaskValidationService $validator)
    {
        $this->tasks = $tasks;
        $this->validator = $validator;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Http\DTO\Task  $data
     * @param  \App\Models\User  $user
     * @param  int|null  $email
     *
     * @return \App\Http\Task
     */
    public function run(TaskData $data, User $user, ?int $email = null)
    {
        $this->validator->validate($data->toArray());

        return $this->tasks->createTaskForUser($data->only(['title', 'description', 'report_to', 'due_date', 'complete', 'category']), $user, $email);
    }
}
