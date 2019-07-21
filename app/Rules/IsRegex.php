<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsRegex implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return @preg_match($value, '') !== FALSE;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The given string is not a valid regular expression.';
    }
}
