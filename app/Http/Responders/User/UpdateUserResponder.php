<?php

namespace App\Http\Responders\User;

use PerfectOblivion\Responder\Responder;

class UpdateUserResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        $this->request->session()->flash('success', 'User information updated successfully!');

        return redirect()->back(303);
    }
}
