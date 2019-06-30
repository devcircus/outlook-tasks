<?php

namespace App\Http\Actions\Auth\Outlook;

use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use League\OAuth2\Client\Provider\GenericProvider;
use App\Http\Responders\Auth\Outlook\GetTokenResponder;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class GetToken extends Action
{
    /** @var \League\OAuth2\Client\Provider\GenericProvider */
    private $oauth;

    /** @var \App\Http\Responders\Auth\Outlook\GetTokenResponder */
    private $responder;

    /**
     * Construct a new Oauth AuthController.
     *
     * @param  \League\OAuth2\Client\Provider\GenericProvider  $oauth
     * @param  \App\Http\Responders\Auth\Outlook\GetTokenResponder  $responder
     */
    public function __construct(GenericProvider $oauth, GetTokenResponder $responder)
    {
        $this->oauth = $oauth;
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if (! $request->has('code')) {
            $request->session()->put('warning', 'Authorization code not available. Please re-authorize.');
        } else {
            if (! $request->has('state') || ($request->state !== $request->session()->get('oauth_state'))) {
                $request->session()->put('warning', 'Application state is invalid. Please re-authorize.');
            }

            $request->session()->forget('oauth_state');

            try {
                $accessToken = $this->oauth->getAccessToken('authorization_code', [
                    'code' => $request->code,
                ]);

                $request->user()->storeOauthTokens($accessToken->getToken(), $accessToken->getRefreshToken(), $accessToken->getExpires());
                $request->session()->put('success', 'Connected to Outlook!');
            } catch (IdentityProviderException $e) {
                $request->session()->put('warning', 'Error authorizing with Outlook. Please try again.');

                return $this->responder;
            }
        }

        return $this->responder;
    }
}
