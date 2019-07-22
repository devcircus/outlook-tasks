<?php

namespace App\Services\CategoryDefinition\Validation;

use App\Rules\IsRegex;
use Illuminate\Contracts\Validation\Validator;
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
            'definition_type' => ['required', 'in:from,subject,body'],
            'rule_type' => ['required', 'in:exact,words,regex'],
            'definition' => ['required', 'string'],
            'optional' => ['required', 'boolean'],
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

    /**
     * Additional validation to perform on the data.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function withValidator(Validator $validator)
    {
        return $validator->sometimes('definition', new IsRegex, function ($input) {
            return 'regex' === $input->rule_type;
        });
    }
}
