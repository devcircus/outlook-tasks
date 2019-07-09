<?php

namespace App\Http\Responders\Task;

use PerfectOblivion\Responder\Responder;

class UpdateTaskResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        return redirect()->back()->with(['success' => 'Task updated successfully!']);
    }
}
