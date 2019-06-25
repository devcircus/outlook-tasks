<?php

namespace App\Http\Actions\Auth\Outlook;

use Inertia\Inertia;
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
     * @return \Illuminate\Http\Response
     *
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function __invoke(Request $request)
    {
        if ($request->has('code')) {
            if (! $request->has('state') || ($request->state !== $request->session()->get('oauth_state'))) {
                return Inertia::render('Home/Index', [
                    'token' => '',
                    'error' => 'State provided in redirect does not match expected value.',
                ]);
            }

            $request->session()->forget('oauth_state');

            try {
                $accessToken = $this->oauth->getAccessToken('authorization_code', [
                    'code' => $request->code,
                ]);
                $request->user()->storeOauthTokens($accessToken->getToken(), $accessToken->getRefreshToken(), $accessToken->getExpires());

                return $this->responder->withPayload($accessToken->getToken())->respond();
            } catch (IdentityProviderException $e) {
                return Inertia::render('Home/Index', [
                    'token' => '',
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return Inertia::render('Home/Index', [
            'token' => '',
            'error' => $request->has('error') ? $request->error : 'Server error',
            'error_description' => $request->has('error') ? $request->error_description : 'An error occurred. Contact the web administrator.',
        ]);
    }
}
