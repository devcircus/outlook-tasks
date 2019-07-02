<?php

namespace App\Http\Actions\Task;

use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Services\Task\ProcessTasksService;
use App\Http\Responders\Task\ProcessTasksResponder;

class ProcessTasks extends Action
{
    /** @var \App\Http\Responders\Task\ProcessTasksResponder */
    private $responder;

    /**
    * Construct a new ProcessTasks action.
    *
    * @param  \App\Http\Responders\Task\ProcessTasksResponder  $responder
    */
    public function __construct(ProcessTasksResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        ProcessTasksService::call($request->user());

        return $this->responder->respond();
    }
}
