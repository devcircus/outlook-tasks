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
        $this->request->session()->flash('success', 'Category stored successfully!');

        return redirect()->back();
    }
}
