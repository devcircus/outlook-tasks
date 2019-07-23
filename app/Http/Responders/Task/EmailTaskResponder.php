<?php

namespace App\Http\Responders\Task;

use PerfectOblivion\Responder\Responder;

class EmailTaskResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function respond()
    {
        $this->request->session()->flash('success', 'Task will be emailed momentarily.');

        return redirect()->back();
    }
}
