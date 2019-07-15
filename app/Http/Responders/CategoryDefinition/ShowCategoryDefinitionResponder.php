<?php

namespace App\Http\Responders\CategoryDefinition;

use Inertia\Inertia;
use PerfectOblivion\Responder\Responder;

class ShowCategoryDefinitionResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        return Inertia::render('CategoryDefinitions/Show', [
            'category' => $this->payload,
        ]);
    }
}
