<?php

namespace App\Http\Actions\Email;

use App\Models\Email;
use PerfectOblivion\Actions\Action;
use App\Services\Email\DeleteEmailService;
use App\Http\Responders\Email\DeleteEmailResponder;

class DeleteEmail extends Action
{
    /** @var \App\Http\Responders\Email\DeleteEmailResponder */
    private $responder;

    /**
    * Construct a new DeleteEmail action.
    *
    * @param  \App\Http\Responders\Email\DeleteEmailResponder  $responder
    */
    public function __construct(DeleteEmailResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \App\Models\Email  $email
     *
     * @return \Illuminate\Http\Respnse
     */
    public function __invoke(Email $email)
    {
        return $this->responder->withPayload(DeleteEmailService::call($email))->respond();
    }
}
