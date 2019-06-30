<?php

namespace App\Http\Responders\Email;

use Inertia\Inertia;
use PerfectOblivion\Responder\Responder;

class ListEmailsResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        return Inertia::render('Emails/Index', [
            'emails' => $this->payload,
        ]);
    }
}
