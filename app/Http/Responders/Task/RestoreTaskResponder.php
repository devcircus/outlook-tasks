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
        $this->request->session()->flash('success', 'Task restored successfully!');

        return redirect()->back();
    }
}
