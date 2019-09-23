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
        $this->request->session()->flash('success', 'Task updated successfully!');

        return redirect()->back(303);
    }
}
