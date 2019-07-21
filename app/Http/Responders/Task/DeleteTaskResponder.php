<?php

namespace App\Http\Responders\Task;

use PerfectOblivion\Responder\Responder;

class DeleteTaskResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        $this->request->session()->flash('success', 'Task deleted successfully!');

        return redirect()->back();
    }
}
