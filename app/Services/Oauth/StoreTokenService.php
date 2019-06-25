<?php

namespace App\Services\Oauth;

use PerfectOblivion\Services\Traits\SelfCallingService;

class StoreTokenService extends TokenService
{
    use SelfCallingService;

    /**
     * Store the oauth tokens in the session.
     *
     * @param  string  $accessToken
     * @param  string  $refreshToken
     * @param  string  $expires
     */
    public function run(string $access_token, string $refresh_token, string $expires)
    {
        $this->session->put('access_token', $access_token);
        $this->session->put('refresh_token', $refresh_token);
        $this->session->put('token_expires', $access_token);
    }
}
