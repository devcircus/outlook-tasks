<?php

namespace App\Http\Responders\Email;

use PerfectOblivion\Responder\Responder;

class DeleteAllEmailResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        $this->request->session()->flash('success', 'All email deleted successfully!');

        return redirect()->back();
    }
}
