<?php

namespace App\Http\Responders\Category;

use PerfectOblivion\Responder\Responder;

class StoreCategoryResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        return redirect()->back()->with(['success' => 'Category created successfully!']);
    }
}
