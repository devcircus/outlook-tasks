<?php

namespace App\Http\Responders\Dashboard;

use Inertia\Inertia;
use PerfectOblivion\Responder\Responder;

class IndexResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return \Illuminate\View\View
     */
    public function respond()
    {
        return Inertia::render('Dashboard/Index', [
            'tasks' => $this->payload
        ]);
    }
}
