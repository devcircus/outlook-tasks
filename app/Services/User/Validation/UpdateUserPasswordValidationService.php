<?php

namespace App\Services\User\Validation;

use PerfectOblivion\Valid\ValidationService\ValidationService;

class UpdateUserPasswordValidationService extends ValidationService
{
    /**
     * Get the validation rules that apply to the data.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => ['required', 'min:8'],
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
            'password' => ['trim', 'strip_tags'],
        ];
    }
}
