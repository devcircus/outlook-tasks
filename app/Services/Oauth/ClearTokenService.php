<?php

namespace App\Services\Oauth;

use PerfectOblivion\Services\Traits\SelfCallingService;

class ClearTokenService extends TokenService
{
    use SelfCallingService;

    /**
     * Clear the oauth tokens from the session.
     */
    public function run()
    {
        $this->session->forget('access_token');
        $this->session->forget('refresh_token');
        $this->session->forget('token_expires');
    }
}
