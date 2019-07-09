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
        return redirect()->route('dashboard')->with(['success' => 'Task created successfully!']);
    }
}
