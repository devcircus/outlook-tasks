<?php

namespace App\Services\Task\Validation;

use App\Rules\CategoryAvailableRule;
use PerfectOblivion\Valid\ValidationService\ValidationService;

class StoreTaskValidationService extends ValidationService
{
    /**
     * Get the validation rules that apply to the data.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'min:3'],
            'description' => ['required', 'string', 'min:6'],
            'category' => ['required', 'string', new CategoryAvailableRule],
            'report_to' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'complete' => ['boolean'],
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
            'title' => ['trim', 'strip_tags', 'capitalize'],
            'description' => ['trim', 'strip_tags'],
            'report_to' => ['trim', 'strip_tags'],
        ];
    }

    /**
     * Get the custom error messages for invalid fields.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'category.string' => 'Invalid category format.',
        ];
    }
}
