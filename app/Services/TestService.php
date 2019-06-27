<?php

namespace App\Services;

use PerfectOblivion\Services\Traits\SelfCallingService;

class TestService
{
    use SelfCallingService;

    /**
     * Construct a new TestService.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the call to the service.
     *
     * @return mixed
     */
    public function run()
    {
        //
    }
}
