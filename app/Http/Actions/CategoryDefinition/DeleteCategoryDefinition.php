<?php

namespace App\Http\Actions\CategoryDefinition;

use App\Models\Definition;
use PerfectOblivion\Actions\Action;
use App\Services\CategoryDefinition\DeleteCategoryDefinitionService;
use App\Http\Responders\CategoryDefinition\DeleteCategoryDefinitionResponder;

class DeleteCategoryDefinition extends Action
{
    /** @var \App\Http\Responders\CategoryDefinition\DeleteCategoryDefinitionResponder */
    private $responder;

    /**
    * Construct a new DeleteCategoryDefinition action.
    *
    * @param  \App\Http\Responders\CategoryDefinition\DeleteCategoryDefinitionResponder  $responder
    */
    public function __construct(DeleteCategoryDefinitionResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \App\Models\Definition  $definition
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Definition $definition)
    {
        DeleteCategoryDefinitionService::call($definition);

        return $this->responder->respond();
    }
}
