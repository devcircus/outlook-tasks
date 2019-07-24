<?php

namespace App\Http\Actions\Task;

use App\Http\DTO\Task;
use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Http\Responders\Task\CreateTaskResponder;

class CreateTask extends Action
{
    /** @var \App\Http\Responders\Task\CreateTaskResponder */
    private $responder;

    /**
     * Construct a new CreateTask action.
     *
     * @param  \App\Http\Responders\Task\CreateTaskResponder  $responder
     */
    public function __construct(CreateTaskResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return $this->responder->respond();
    }
}
