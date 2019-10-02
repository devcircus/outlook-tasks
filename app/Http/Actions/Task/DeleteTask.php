<?php

namespace App\Http\Actions\Task;

use App\Models\Task;
use PerfectOblivion\Actions\Action;
use App\Services\Task\DeleteTaskService;
use App\Http\Responders\Task\DeleteTaskResponder;
use Illuminate\Http\Request;

class DeleteTask extends Action
{
    /** @var \App\Http\Responders\Task\DeleteTaskResponder */
    private $responder;

    /**
     * Construct a new DeleteTask action.
     *
     * @param  \App\Http\Responders\Task\DeleteTaskResponder  $responder
     */
    public function __construct(DeleteTaskResponder $responder)
    {
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
        return $this->responder->withPayload(DeleteTaskService::call($task, $request->user()))->respond();
    }
}
