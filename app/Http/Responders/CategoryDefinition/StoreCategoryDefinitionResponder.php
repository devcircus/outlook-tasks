<?php

namespace App\Http\Responders\CategoryDefinition;

use PerfectOblivion\Responder\Responder;

class StoreCategoryDefinitionResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function respond()
    {
        return redirect()->back()->with(['success' => 'Definition created successfully!']);
    }
}
