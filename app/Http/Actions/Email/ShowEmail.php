<?php

namespace App\Http\Actions\Email;

use App\Models\Email;
use PerfectOblivion\Actions\Action;
use App\Http\Responders\Email\ShowEmailResponder;

class ShowEmail extends Action
{
    /** @var \App\Http\Responders\Email\ShowEmailResponder */
    private $responder;

    /**
    * Construct a new ShowEmail action.
    *
    * @param  \App\Http\Responders\Email\ShowEmailResponder  $responder
    */
    public function __construct(ShowEmailResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \App\Models\Email  $email
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Email $email)
    {
        return $this->responder->withPayload($email)->respond();
    }
}
