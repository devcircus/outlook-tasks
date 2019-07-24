<?php

namespace App\Services\CategoryDefinition;

use App\Models\Definition;
use App\Http\DTO\CategoryDefinition;
use PerfectOblivion\Services\Traits\SelfCallingService;
use App\Services\CategoryDefinition\Validation\UpdateCategoryDefinitionValidationService;

class UpdateCategoryDefinitionService
{
    use SelfCallingService;

    /** @var \App\Services\CategoryDefinition\Validation\UpdateCategoryDefinitionValidationService */
    private $validator;

    /**
     * Construct a new UpdateCategoryDefinitionService.
     *
     * @param  \App\Services\CategoryDefinition\Validation\UpdateCategoryDefinitionValidationService  $validator
     */
    public function __construct(UpdateCategoryDefinitionValidationService $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\Definition  $defintion
     * @param  \App\Http\DTO\CategoryDefinition  $data
     *
     * @return mixed
     */
    public function run(Definition $definition, CategoryDefinition $data)
    {
        $this->validator->validate($data->toArray());

        return $definition->updateDefinition($data->toArray());
    }
}
