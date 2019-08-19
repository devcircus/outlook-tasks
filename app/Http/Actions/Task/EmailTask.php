<?php

namespace App\Http\Actions\Task;

use App\Models\Task;
use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Services\Task\EmailTaskService;
use App\Http\Responders\Task\EmailTaskResponder;

class EmailTask extends Action
{
    /** @var \App\Http\Responders\Task\EmailTaskResponder */
    private $responder;

    /**
     * Construct a new EmailTask action.
     *
     * @param  \App\Http\Responders\Task\EmailTaskResponder  $responder
     */
    public function __construct(EmailTaskResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     */
    public function __invoke(Request $request, Task $task)
    {
        EmailTaskService::call($request->only(['to', 'due_date', 'subject', 'body']), $request->user());

        return $this->responder->respond();
    }
}
