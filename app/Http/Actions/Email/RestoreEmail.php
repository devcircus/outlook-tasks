<?php

namespace App\Http\Actions\Email;

use App\Models\Email;
use PerfectOblivion\Actions\Action;
use App\Services\Email\RestoreEmailService;
use App\Http\Responders\Email\RestoreEmailResponder;

class RestoreEmail extends Action
{
    /** @var \App\Http\Responders\Email\RestoreEmailResponder */
    private $responder;

    /**
     * Construct a new RestoreEmail action.
     *
     * @param  \App\Http\Responders\Email\RestoreEmailResponder  $responder
     */
    public function __construct(RestoreEmailResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \App\Models\Email  $email
     */
    public function __invoke(Email $email)
    {
        $restored = RestoreEmailService::call($email);

        return $this->responder->withPayload($restored)->respond();
    }
}
