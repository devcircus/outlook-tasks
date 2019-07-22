<?php

namespace App\Http\Responders\Email;

use PerfectOblivion\Responder\Responder;

class RestoreEmailResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function respond()
    {
        $this->request->session()->flash('success', 'Email restored successfully!');

        return redirect()->back();
    }
}
