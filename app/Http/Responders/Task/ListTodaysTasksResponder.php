<?php

namespace App\Http\Responders\Task;

use Inertia\Inertia;
use Inertia\Response;
use PerfectOblivion\Responder\Responder;

class ListTodaysTasksResponder extends Responder
{
    /**
     * Send a response.
     */
    public function respond(): Response
    {
        return Inertia::render('Tasks/Index', [
            'tasks' => $this->payload,
        ]);
    }
}
