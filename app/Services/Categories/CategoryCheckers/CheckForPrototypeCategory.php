<?php

namespace App\Services\Categories\CategoryCheckers;

use App\Models\Email;
use Illuminate\Support\Str;
use PerfectOblivion\Services\Traits\SelfCallingService;

class CheckForPrototypeCategory
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

        if (config('outlook.categories.prototype.from_address') === strtolower($from)) {
            if (Str::contains(strtolower($body), config('outlook.categories.prototype.body'))) {
                return true;
            }
        }

        return false;
    }
}
