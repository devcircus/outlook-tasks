<?php

namespace App\Http\Actions\Task;

use App\Models\Task;
use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Services\Task\RestoreTaskService;
use App\Http\Responders\Task\RestoreTaskResponder;

class RestoreTask extends Action
{
    /** @var \App\Http\Responders\Task\RestoreTaskResponder */
    private $responder;

    /**
    * Construct a new RestoreTask action.
    *
    * @param  \App\Http\Responders\Task\RestoreTaskResponder  $responder
    */
    public function __construct(RestoreTaskResponder $responder)
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
        $restored = RestoreTaskService::call($task, $request->user());

        return $this->responder->withPayload($restored)->respond();
    }
}
