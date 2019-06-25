<?php

namespace App\Http\Responders\Auth\Outlook;

use PerfectOblivion\Responder\Responder;
use Inertia\Inertia;

class GetTokenResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        return Inertia::render('Home/Index', [
            'token' => $this->payload,
        ]);
    }
}
