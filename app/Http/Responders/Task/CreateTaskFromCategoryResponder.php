<?php

namespace App\Http\Responders\Task;

use Inertia\Inertia;
use Inertia\Response;
use PerfectOblivion\Responder\Responder;

class CreateTaskFromCategoryResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond() :Response
    {
        return Inertia::render('Tasks/Create', ['task' => $this->payload]);
    }
}
