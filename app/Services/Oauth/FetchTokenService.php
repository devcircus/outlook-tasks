<?php

namespace App\Services\Oauth;

use PerfectOblivion\Services\Traits\SelfCallingService;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class FetchTokenService extends TokenService
{
    use SelfCallingService;

    /**
     * Check the current oauth token and refresh if necessary.
     *
     * @return string
     *
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function run()
    {
        if (! $this->hasOauthTokens()) {
            return '';
        }

        if ($this->getTokenExpires() <= time() + 300) {
            try {
                $newToken = resolve('oauth')->getAccessToken('refresh_token', [
                    'refresh_token' => $this->getRefreshToken(),
                ]);

                StoreTokenService::call($newToken->getToken(), $newToken->getRefreshToken(), $newToken->getExpires());

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
}
