<?php

namespace App\Http\Actions\CategoryDefinition;

use App\Models\Definition;
use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Http\DTO\CategoryDefinitionData;
use Illuminate\Contracts\Auth\Access\Gate;
use App\Services\CategoryDefinition\UpdateCategoryDefinitionService;
use App\Http\Responders\CategoryDefinition\UpdateCategoryDefinitionResponder;

class UpdateCategoryDefinition extends Action
{
    /** @var \Illuminate\Contracts\Auth\Access\Gate */
    private $gate;

    /** @var \App\Http\Responders\CategoryDefinition\UpdateCategoryDefinitionResponder */
    private $responder;

    /**
    * Construct a new UpdateCategoryDefinition action.
    *
    * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
    * @param  \App\Http\Responders\CategoryDefinition\UpdateCategoryDefinitionResponder  $responder
    */
    public function __construct(Gate $gate, UpdateCategoryDefinitionResponder $responder)
    {
        $this->gate = $gate;
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Definition  $definition
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Definition $definition)
    {
        if (! $this->gate->allows('update-category-definition', $definition)) {
            return redirect()->back()->with(['warning' => 'You do not have permission to edit this definition.']);
        }

        $updated = UpdateCategoryDefinitionService::call($definition, CategoryDefinitionData::fromRequest($request));

        return $this->responder->withPayload($updated)->respond();
    }
}
