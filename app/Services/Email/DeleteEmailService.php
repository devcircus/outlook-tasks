<?php

namespace App\Services\Email;

use PerfectOblivion\Services\Traits\SelfCallingService;
use App\Models\Email;

class DeleteEmailService
{
    use SelfCallingService;

    /**
     * Handle the call to the service.
     *
     * @return \App\Models\Email
     */
    public function run(Email $email)
    {
        return $email->deleteEmail();
    }
}
