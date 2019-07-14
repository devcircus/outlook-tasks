<?php

namespace App\Http\Responders\Admin;

use Inertia\Inertia;
use PerfectOblivion\Responder\Responder;

class IndexResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        return Inertia::render('Admin/Index', [
            'taskCategories' => $this->payload,
        ]);
    }
}
