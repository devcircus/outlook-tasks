<?php

namespace App\Http\Responders\Category;

use PerfectOblivion\Responder\Responder;

class DeleteCategoryResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function respond()
    {
        $this->request->session()->flash('success', 'Category deleted successfully!');

        return redirect()->back();
    }
}
