<?php

namespace App\Http\Actions\Auth\Outlook;

use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use League\OAuth2\Client\Provider\GenericProvider;
use App\Http\Responders\Auth\Outlook\SignInResponder;

class SignIn extends Action
{
    /** @var \League\OAuth2\Client\Provider\GenericProvider */
    private $oauth;

    /** @var \App\Http\Responders\Auth\Outlook\SignInResponder */
    private $responder;

    /**
     * Construct a new Oauth AuthController.
     *
     * @param  \League\OAuth2\Client\Provider\GenericProvider  $oauth
     * @param  \App\Http\Responders\Auth\Outlook\SignInResponder  $responder
     */
    public function __construct(GenericProvider $oauth, SignInResponder $responder)
    {
        $this->oauth = $oauth;
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        $authorizationUrl = $this->oauth->getAuthorizationUrl();

        $request->session()->put('oauth_state', $this->oauth->getState());

        return $this->responder->withPayload($authorizationUrl);
    }
}
