<?php

namespace App\Services\CategoryDefinition;

use App\Models\Category;
use App\Http\DTO\CategoryDefinitionData;
use PerfectOblivion\Services\Traits\SelfCallingService;
use App\Services\CategoryDefinition\Validation\StoreCategoryDefinitionValidationService;

class StoreCategoryDefinitionService
{
    use SelfCallingService;

    /** @var \App\Services\CategoryDefinition\Validation\StoreCategoryDefinitionValidationService */
    private $validator;

    /**
     * Construct a new StoreCategoryDefinitionService.
     *
     * @param  \App\Services\CategoryDefinition\Validation\StoreCategoryDefinitionValidationService  $validator
     */
    public function __construct(StoreCategoryDefinitionValidationService $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Http\DTO\CategoryDefinitionData  $data
     * @param  \App\Models\Category  $category
     *
     * @return \App\Models\CategoryDefinition
     */
    public function run(CategoryDefinitionData $data, Category $category)
    {
        $this->validator->validate($data->toArray());

        return $category->storeDefinition($data->only(['definition_type', 'rule_type', 'definition']));
    }
}
