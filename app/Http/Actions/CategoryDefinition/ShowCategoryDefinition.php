<?php

namespace App\Http\Actions\CategoryDefinition;

use App\Models\Category;
use PerfectOblivion\Actions\Action;
use App\Http\Responders\CategoryDefinition\ShowCategoryDefinitionResponder;

class ShowCategoryDefinition extends Action
{
    /** @var \App\Http\Responders\CategoryDefinition\ShowCategoryDefinitionResponder */
    private $responder;

    /**
     * Construct a new ShowCategoryDefinition action.
     *
     * @param  \App\Http\Responders\CategoryDefinition\ShowCategoryDefinitionResponder  $responder
     */
    public function __construct(ShowCategoryDefinitionResponder $responder)
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
