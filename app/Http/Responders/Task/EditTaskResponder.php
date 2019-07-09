<?php

namespace App\Http\Responders\Task;

use Inertia\Inertia;
use PerfectOblivion\Responder\Responder;

class EditTaskResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        return Inertia::render('Tasks/Edit', [
            'task' => $this->payload,
        ]);
    }
}
