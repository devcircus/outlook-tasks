<?php

namespace App\Services\CategoryDefinition;

use App\Models\Definition;
use PerfectOblivion\Services\Traits\SelfCallingService;

class DeleteCategoryDefinitionService
{
    use SelfCallingService;

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\Definition  $definition
     *
     * @return bool
     */
    public function run(Definition $definition)
    {
        return $definition->deleteDefinition();
    }
}
