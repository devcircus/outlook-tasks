<?php

namespace App\Services\Email;

use App\Models\Email;
use PerfectOblivion\Services\Traits\SelfCallingService;

class RestoreEmailService
{
    use SelfCallingService;

    /**
     * Construct a new RestoreEmailService.
     */
    public function __construct()
    {
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\Email  $email
     *
     * @return \App\Models\Email
     */
    public function run(Email $email)
    {
        return $email->restoreEmail();
    }
}
