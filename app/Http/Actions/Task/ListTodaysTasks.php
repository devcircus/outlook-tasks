<?php

namespace App\Http\Actions\Task;

use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Services\Task\ListTodaysTasksService;
use App\Http\Responders\Task\ListTodaysTasksResponder;

class ListTodaysTasks extends Action
{
    /** @var \App\Http\Responders\Task\ListTodaysTasksResponder */
    private $responder;

    /**
     * Construct a new ListTodaysTasks action.
     *
     * @param  \App\Http\Responders\Task\ListTodaysTasksResponder  $responder
     */
    public function __construct(ListTodaysTasksResponder $responder)
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
        return $this->responder->withPayload(ListTodaysTasksService::call($request->user()))->respond();
    }
}
