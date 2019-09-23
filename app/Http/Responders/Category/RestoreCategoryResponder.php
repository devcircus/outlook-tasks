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
        $this->request->session()->flash('success', 'Category restored successfully!');

        return redirect()->back(303);
    }
}
