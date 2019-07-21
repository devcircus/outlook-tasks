<?php

namespace App\Services\CategoryDefinition\Validation;

use PerfectOblivion\Valid\ValidationService\ValidationService;

class UpdateCategoryDefinitionValidationService extends ValidationService
{
    /**
     * Get the validation rules that apply to the data.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'definition_type' => ['required', 'in:fromDefinition,subjectDefinition,bodyDefinition'],
            'rule_type' => ['required', 'in:exact,words,regex'],
            'definition' => ['required', 'string'],
        ];
    }

    /**
     * Get the sanitization filters that apply to the data.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'definition_type' => ['strip_tags', 'trim'],
            'rule_type' => ['strip_tags', 'trim'],
            'definition' => ['strip_tags'],
        ];
    }
}
