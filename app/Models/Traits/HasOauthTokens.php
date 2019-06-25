<?php

namespace App\Models\Traits;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

trait HasOauthTokens
{
    /**
     * Does the user have oauth tokens currently available?
     *
     * @return boolean
     */
    public function hasOauthTokens()
    {
        if ($this->outlook_access_token && $this->outlook_refresh_token && $this->outlook_token_expires) {
            return true;
        }

        $this->deleteOauthTokens();

        return false;
    }

    /**
     * Store the oauth tokens for the user.
     *
     * @param  string  $accessToken
     * @param  string  $refreshToken
     * @param  string  $tokenExpires
     */
    public function storeOauthTokens(string $accessToken, string $refreshToken, string $tokenExpires)
    {
        $this->outlook_access_token = $accessToken;
        $this->outlook_refresh_token = $refreshToken;
        $this->outlook_token_expires = $tokenExpires;

        return $this->save();
    }

    /**
     * Check the current oauth token and refresh if necessary.
     *
     * @return string
     */
    public function fetchOauthToken()
    {
        if (! $this->hasOauthTokens()) {
            return '';
        }

        if ($this->getTokenExpires() <= now()->addMinutes(5)) {
            try {
                $newToken = resolve('oauth')->getAccessToken('refresh_token', [
                    'refresh_token' => $this->getRefreshToken(),
                ]);

                $this->storeOauthTokens($newToken->getToken(), $newToken->getRefreshToken(), $newToken->getExpires());

                return $newToken->getToken();
            }
            catch (IdentityProviderException $e) {
                return '';
            }
        }
        else {
            return $this->getAccessToken();
        }
    }

    /**
     * Get the token_expires value.
     *
     * @return string
     */
    public function getTokenExpires()
    {
        return $this->outlook_token_expires;
    }

    /**
     * Get the refresh_token value.
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->outlook_refresh_token;
    }

    /**
     * Get the access_token value.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->outlook_access_token;
    }

    /**
     * Delete the user's oauth tokens.
     *
     * @return bool
     */
    public function deleteOauthTokens()
    {
        $this->outlook_access_token = null;
        $this->outlook_refresh_token = null;
        $this->outlook_token_expires = null;

        return $this->save();
    }
}
