<?php

namespace App\Http\Responders\CategoryDefinition;

use PerfectOblivion\Responder\Responder;

class UpdateCategoryDefinitionResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function respond()
    {
        $this->request->session()->flash('success', 'Definition updated successfully!');

        return redirect()->route('categories.show', $this->payload->category_id, 303);
    }
}
