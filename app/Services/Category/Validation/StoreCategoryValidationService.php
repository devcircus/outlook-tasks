<?php

namespace App\Services\Category\Validation;

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
            'name' => ['string', 'min:3',],
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
}
