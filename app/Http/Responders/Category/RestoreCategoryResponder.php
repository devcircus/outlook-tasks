<?php

namespace App\Http\Responders\Category;

use PerfectOblivion\Responder\Responder;

class RestoreCategoryResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function respond()
    {
        return redirect()->back()->with(['success' => 'Category successfully restored.']);
    }
}
