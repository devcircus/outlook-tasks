<?php

namespace App\Services\CategoryDefinition\Validation;

use PerfectOblivion\Valid\ValidationService\ValidationService;

class StoreCategoryDefinitionValidationService extends ValidationService
{
    /**
     * Get the validation rules that apply to the data.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'words' => ['string', 'nullable'],
            'exact' => ['string', 'nullable'],
            'regex' => ['string', 'nullable'],
            'type' => ['required', 'in:fromDefinition,subjectDefinition,bodyDefinition'],
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
            'words' => ['strip_tags'],
            'exact' => ['strip_tags'],
            'regex' => ['strip_tags'],
        ];
    }
}
