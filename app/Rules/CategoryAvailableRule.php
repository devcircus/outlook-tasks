<?php

namespace App\Rules;

use PerfectOblivion\Valid\CustomRule;

class CategoryAvailableRule extends CustomRule
{
    /** @var array */
    private $categories;

    /**
     * Create a new rule instance.
     */
    public function __construct()
    {
        $this->categories = resolve('categories');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, array_keys($this->categories->toArray()));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid category.';
    }
}
