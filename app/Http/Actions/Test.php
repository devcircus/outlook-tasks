<?php

namespace App\Http\Actions;

use PerfectOblivion\Actions\Action;
use PerfectOblivion\Payload\Payload;
use App\Http\Responders\TestResponder;

class Test extends Action
{
    /** @var \App\Http\Responders\TestResponder */
    private $responder;

    /**
     * Construct a new Test action.
     *
     * @param  \App\Http\Responders\TestResponder  $responder
     */
    public function __construct(TestResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     */
    public function __invoke()
    {
        $data = ['name' => 'Clay', 'age' => 42];
        $payload = (new Payload)->setOutput($data)->setStatus(404)->setMessages(['message' => 'An error occurred.']);

        return $this->responder->withPayload($payload);
    }
}
