<?php

namespace App\Http\Responders\Email;

use Inertia\Inertia;
use PerfectOblivion\Responder\Responder;

class ShowEmailResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return \Illuminate\View\View
     */
    public function respond()
    {
        return Inertia::render('Emails/Show', [
            'email' => $this->payload,
        ]);
    }
}
