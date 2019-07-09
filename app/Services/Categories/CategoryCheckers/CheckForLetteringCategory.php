<?php

namespace App\Services\Categories\CategoryCheckers;

use App\Models\Email;
use Illuminate\Support\Str;
use PerfectOblivion\Services\Traits\SelfCallingService;

class CheckForLetteringCategory
{
    use SelfCallingService;

    /**
     * Check if the email should be assigned the lettering category.
     *
     * @param  \App\Models\Email  $email
     *
     * @return bool
     */
    public function run(Email $email)
    {
        $from = $email->from_address;
        $subject = $email->subject;

        if (config('outlook.categories.lettering.from_address') === strtolower($from)) {
            if (Str::contains(strtolower($subject), config('outlook.categories.lettering.subject'))) {
                return true;
            }
        }

        return false;
    }
}
