<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Http\DTO\Task as TaskData;
use PerfectOblivion\Services\Traits\SelfCallingService;
use App\Services\Task\Validation\UpdateTaskValidationService;

class UpdateTaskService
{
    use SelfCallingService;

    /** @var \App\Services\Task\Validation\UpdateTaskValidationService */
    private $validator;

    /**
     * Construct a new UpdateTaskService.
     *
     * @param  \App\Services\Task\Validation\UpdateTaskValidationService  $validator
     */
    public function __construct(UpdateTaskValidationService $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\Task  $task
     * @param  \App\Http\DTO\Task  $data
     *
     * @return mixed
     */
    public function run(Task $task, TaskData $data)
    {
        $this->validator->validate($data->toArray());

        return $task->updateTaskData($data->only(['title', 'description', 'report_to', 'due_date', 'complete']));
    }
}
