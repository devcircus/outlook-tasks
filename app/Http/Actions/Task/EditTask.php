<?php

namespace App\Http\Actions\Task;

use App\Models\Task;
use PerfectOblivion\Actions\Action;
use App\Http\Responders\Task\EditTaskResponder;

class EditTask extends Action
{
    /** @var \App\Http\Responders\Task\EditTaskResponder */
    private $responder;

    /**
     * Construct a new EditTask action.
     *
     * @param  \App\Http\Responders\Task\EditTaskResponder  $responder
     */
    public function __construct(EditTaskResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \App\Models\Task  $task
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Task $task)
    {
        return $this->responder->withPayload($task)->respond();
    }
}
