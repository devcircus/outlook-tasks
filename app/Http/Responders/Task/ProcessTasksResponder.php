<?php

namespace App\Http\Responders\Task;

use PerfectOblivion\Responder\Responder;

class ProcessTasksResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        return redirect()->route('dashboard');
    }
}
