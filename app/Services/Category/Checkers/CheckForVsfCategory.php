<?php

namespace App\Services\Category\Checkers;

use App\Models\Email;
use Illuminate\Support\Str;
use PerfectOblivion\Services\Traits\SelfCallingService;

class CheckForVsfCategory extends Checker
{
    use SelfCallingService;

    /**
     * Check if the email should be assigned to the VSF category.
     *
     * @param  \App\Models\Email  $email
     *
     * @return bool
     */
    public function run(Email $email)
    {
        $subject = $email->subject;
        $body = $email->body;
        $from = $email->from_address;

        if (config('outlook.categories.vsf.from_address') === strtolower($from)) {
            if (Str::contains(strtolower($subject), config('outlook.categories.vsf.subject'))) {
                return true;
            }
            if (Str::contains(strtolower($body), config('outlook.categories.vsf.body'))) {
                return true;
            }
        }

        return false;
    }
}
