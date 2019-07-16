<?php

namespace App\Http\Responders\Category;

use Inertia\Inertia;
use PerfectOblivion\Responder\Responder;

class ShowCategoryResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        return Inertia::render('Categories/Edit', [
            'category' => $this->payload,
        ]);
    }
}
