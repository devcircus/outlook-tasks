<?php

namespace App\Http\Responders\User;

use PerfectOblivion\Responder\Responder;

class DeleteUserResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        $this->request->session()->flash('success', 'User deleted successfully!');

        return redirect()->back(303);
    }
}
