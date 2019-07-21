<?php

namespace App\Http\Responders\Task;

use PerfectOblivion\Responder\Responder;

class StoreTaskResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function respond()
    {
        $this->request->session()->flash('success', 'Task created successfully!');

        return redirect()->route('dashboard');
    }
}
