<?php

namespace App\Http\Responders\Task;

use PerfectOblivion\Responder\Responder;

class RestoreTaskResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function respond()
    {
        return redirect()->back()->with(['success' => 'Task successfully restored.']);
    }
}
