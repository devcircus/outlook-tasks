<?php

namespace App\Http\Responders\Email;

use PerfectOblivion\Responder\Responder;

class DeleteEmailResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function respond()
    {
        return redirect()->route('dashboard')->with(['success' => 'Email successfully deleted!']);
    }
}
