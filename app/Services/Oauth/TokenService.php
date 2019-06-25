<?php

namespace App\Services\Oauth;

use Illuminate\Session\SessionManager;

class TokenService
{
    /** @var \Illuminate\Session\SessionManager */
    protected $session;

    /**
     * Construct a new StoreTokenService.
     *
     * @param  \Illuminate\Session\SessionManager  $session
     */
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * Are Oauth tokens currently available?
     *
     * @return boolean
     */
    public function hasOauthTokens()
    {
        if ($this->session->has('access_token') && $this->session->has('refresh_token') && $this->session->has('token_expires')) {
            return true;
        }

        ClearTokenService::call();

        return false;
    }

    /**
     * Get the token_expires value.
     *
     * @return string
     */
    public function getTokenExpires()
    {
        return $this->session->get('token_expires');
    }

    /**
     * Get the refresh_token value.
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->session->get('refresh_token');
    }

    /**
     * Get the access_token value.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->session->get('access_token');
    }
}
