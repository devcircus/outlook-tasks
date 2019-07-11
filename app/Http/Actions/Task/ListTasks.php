<?php

namespace App\Http\Actions\Task;

use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Services\Task\ListTasksService;
use App\Http\Responders\Task\ListTasksResponder;

class ListTasks extends Action
{
    /** @var \App\Http\Responders\Task\ListTasksResponder */
    private $responder;

    /**
    * Construct a new ListTasks action.
    *
    * @param  \App\Http\Responders\Task\ListTasksResponder  $responder
    */
    public function __construct(ListTasksResponder $responder)
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
        return $this->responder->withPayload(ListTasksService::call($request->user()))->respond();
    }
}
