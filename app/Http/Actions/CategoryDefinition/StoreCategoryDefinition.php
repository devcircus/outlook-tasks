<?php

namespace App\Http\Actions\CategoryDefinition;

use App\Models\Category;
use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Http\DTO\CategoryDefinition;
use App\Services\CategoryDefinition\StoreCategoryDefinitionService;
use App\Http\Responders\CategoryDefinition\StoreCategoryDefinitionResponder;

class StoreCategoryDefinition extends Action
{
    /** @var \App\Http\Responders\CategoryDefinition\StoreCategoryDefinitionResponder */
    private $responder;

    /**
     * Construct a new StoreCategoryDefinition action.
     *
     * @param  \App\Http\Responders\CategoryDefinition\StoreCategoryDefinitionResponder  $responder
     */
    public function __construct(StoreCategoryDefinitionResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Category $category)
    {
        $data = CategoryDefinition::fromRequest($request);

        StoreCategoryDefinitionService::call($data, $category);

        return $this->responder->respond();
    }
}
