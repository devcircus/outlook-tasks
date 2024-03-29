<?php

namespace App\Http\Responders\User;

use PerfectOblivion\Responder\Responder;

class StoreUserResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return \Illuminate\View\View
     */
    public function respond()
    {
        $this->request->session()->flash('success', 'User created successfully!');

        return redirect()->route('users.list');
    }
}
