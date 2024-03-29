<?php

namespace App\Services\Category\Validation;

use Illuminate\Validation\Rule;
use PerfectOblivion\Valid\ValidationService\ValidationService;

class StoreCategoryValidationService extends ValidationService
{
    /**
     * Get the validation rules that apply to the data.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['string', 'min:3', Rule::unique('categories', 'name')],
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
            'name' => ['strip_tags', 'trim'],
        ];
    }

    /**
     * Get the custom error messages for fields.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => 'A category with that name already exists. If the category has been archived, you may restore it.',
        ];
    }
}
