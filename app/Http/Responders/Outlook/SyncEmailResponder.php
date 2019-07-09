<?php

namespace App\Http\Responders\Outlook;

use PerfectOblivion\Responder\Responder;

class SyncEmailResponder extends Responder
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
