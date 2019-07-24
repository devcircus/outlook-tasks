<?php

namespace App\Http\Actions\Task;

use App\Http\DTO\Task;
use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Services\Task\StoreTaskService;
use App\Http\Responders\Task\StoreTaskResponder;

class StoreTask extends Action
{
    /** @var \App\Http\Responders\Task\StoreTaskResponder */
    private $responder;

    /**
    * Construct a new StoreTask action.
    *
    * @param  \App\Http\Responders\Task\StoreTaskResponder  $responder
    */
    public function __construct(StoreTaskResponder $responder)
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
        $task = StoreTaskService::call(
            Task::fromRequest($request),
            $request->user(),
            $request->input('email_id')
        );

        return $this->responder->withPayload($task)->respond();
    }
}
