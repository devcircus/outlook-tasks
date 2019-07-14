<?php

namespace App\Services\Category\Checkers;

use App\Models\Email;
use Illuminate\Support\Str;
use PerfectOblivion\Services\Traits\SelfCallingService;

class CheckForProductionCategory
{
    use SelfCallingService;

    /**
     * Check if the email should be assigned the prototype category.
     *
     * @param  \App\Models\Email  $email
     *
     * @return bool
     */
    public function run(Email $email)
    {
        $body = $email->body;
        $from = $email->from_address;

        if (config('outlook.categories.production.from_address') === strtolower($from)) {
            if (Str::contains(strtolower($body), config('outlook.categories.production.body'))) {
                return true;
            }
        }

        return false;
    }
}
