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
        $this->request->session()->flash('success', 'Email deleted successfully!');

        return redirect()->back(303);
    }
}
