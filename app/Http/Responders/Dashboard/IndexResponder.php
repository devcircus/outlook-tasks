<?php

namespace App\Http\Responders\Dashboard;

use Inertia\Inertia;
use Inertia\Response;
use PerfectOblivion\Responder\Responder;

class IndexResponder extends Responder
{
    /**
     * Send a response.
     */
    public function respond(): Response
    {
        return Inertia::render('Dashboard/Index', $this->payload);
    }
}
