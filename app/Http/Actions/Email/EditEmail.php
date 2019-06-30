<?php

namespace App\Http\Actions\Email;

use PerfectOblivion\Actions\Action;
use App\Http\Responders\Email\EditEmailResponder;

class EditEmail extends Action
{
    /** @var \App\Http\Responders\Email\EditEmailResponder */
    private $responder;

    /**
    * Construct a new EditEmail action.
    *
    * @param  \App\Http\Responders\Email\EditEmailResponder  $responder
    */
    public function __construct(EditEmailResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     */
    public function __invoke()
    {
        return $this->responder->respond();
    }
}
