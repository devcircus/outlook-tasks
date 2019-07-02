<?php

namespace App\Services\Categories\CategoryCheckers;

use App\Models\Email;
use Illuminate\Support\Str;
use PerfectOblivion\Services\Traits\SelfCallingService;

class CheckForSwatchCategory
{
    use SelfCallingService;

    /**
     * Check if the email should be assigned the swatch category.
     *
     * @param  \App\Models\Email  $email
     *
     * @return bool
     */
    public function run(Email $email)
    {
        $subject = $email->subject;
        $body = $email->body;

        if (Str::contains(strtolower($subject), config('outlook.categories.swatch.subject'))) {
            if (Str::contains(strtolower($body), config('outlook.categories.swatch.body'))) {
                return true;
            }
        }

        return false;
    }
}
