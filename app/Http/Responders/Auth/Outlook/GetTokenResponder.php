<?php

namespace App\Http\Responders\Auth\Outlook;

use PerfectOblivion\Responder\Responder;

class GetTokenResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        return redirect()->route('dashboard');
    }
}
