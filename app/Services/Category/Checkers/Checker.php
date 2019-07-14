<?php

namespace App\Services\Category\Checkers;

use App\Models\Email;

class Checker
{
    /**
     * Check if the category applies to the given email.
     *
     * @param  \App\Models\Email  $email
     *
     * @return bool
     */
    public static function check(Email $email)
    {
        return self::call($email);
    }
}
