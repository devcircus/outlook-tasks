<?php

namespace App\Http\Actions\Task;

use App\Models\Task;
use App\Http\DTO\TaskData;
use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Services\Task\UpdateTaskService;
use Illuminate\Contracts\Auth\Access\Gate;
use App\Http\Responders\Task\UpdateTaskResponder;

class UpdateTask extends Action
{
    /** @var \Illuminate\Contracts\Auth\Access\Gate */
    private $gate;

    /** @var \App\Http\Responders\Task\UpdateTaskResponder */
    private $responder;

    /**
     * Construct a new UpdateTask action.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @param  \App\Http\Responders\Task\UpdateTaskResponder  $responder
     */
    public function __construct(Gate $gate, UpdateTaskResponder $responder)
    {
        $this->gate = $gate;
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Task $task)
    {
        if (! $this->gate->allows('update-task', $task)) {
            return redirect()->back()->with(['warning' => 'You do not have permission to edit this task.']);
        }

        $updated = UpdateTaskService::call($task, TaskData::fromRequest($request));

        return $this->responder->withPayload($updated)->respond();
    }
}
