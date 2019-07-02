<?php

namespace App\Services\Categories\CategoryCheckers;

use App\Models\Email;
use PerfectOblivion\Services\Traits\SelfCallingService;

class CheckForNoCategory
{
    use SelfCallingService;

    /**
     * Check if the email should not be assigned a category.
     *
     * @param  \App\Models\Email  $email
     *
     * @return bool
     */
    public function run(Email $email)
    {
        return false;
    }
}
