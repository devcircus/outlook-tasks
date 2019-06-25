<?php

namespace App\Http\Responders\Auth\Outlook;

use PerfectOblivion\Responder\Responder;

class SignInResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function respond()
    {
        return redirect($this->payload);
    }
}
