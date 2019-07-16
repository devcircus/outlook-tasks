<?php

namespace App\Http\Actions\Category;

use App\Models\Category;
use PerfectOblivion\Actions\Action;
use App\Http\Responders\Category\ShowCategoryResponder;

class ShowCategory extends Action
{
    /** @var \App\Http\Responders\Category\ShowCategoryResponder */
    private $responder;

    /**
     * Construct a new ShowCategory action.
     *
     * @param  \App\Http\Responders\Category\ShowCategoryResponder  $responder
     */
    public function __construct(ShowCategoryResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \App\Models\Category  $category
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Category $category)
    {
        return $this->responder->withPayload($category)->respond();
    }
}
