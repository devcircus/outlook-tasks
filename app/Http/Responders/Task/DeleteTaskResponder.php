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
        return redirect()->back()->with(['success' => 'Task deleted successfully!']);
    }
}