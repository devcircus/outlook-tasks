<?php

namespace App\Http\Responders\Task;

use PerfectOblivion\Responder\Responder;
use Inertia\Inertia;

class CreateTaskResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        return Inertia::render('Tasks/Create');
    }
}
