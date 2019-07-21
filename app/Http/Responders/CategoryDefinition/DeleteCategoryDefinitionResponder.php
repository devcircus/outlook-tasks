<?php

namespace App\Http\Responders\CategoryDefinition;

use PerfectOblivion\Responder\Responder;

class DeleteCategoryDefinitionResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function respond()
    {
        $this->request->session()->flash('success', 'Definition successfully deleted!');

        return redirect()->back();
    }
}
