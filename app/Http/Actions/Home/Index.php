<?php

namespace App\Http\Actions\Home;

use PerfectOblivion\Actions\Action;
use App\Services\Oauth\TokenService;
use App\Services\Oauth\FetchTokenService;
use App\Http\Responders\Home\IndexResponder;

class Index extends Action
{
    /** @var \App\Http\Responders\Home\IndexResponder */
    private $responder;


    /** @var \App\Services\Oauth\TokenService */
    private $tokenService;

    /**
     * Construct a new Home Index action.
     *
     * @param  \App\Http\Responders\Home\IndexResponder  $responder
     * @param  \App\Services\Oauth\TokenService  $tokenService
     */
    public function __construct(IndexResponder $responder, TokenService $tokenService)
    {
        $this->responder = $responder;
        $this->tokens = $tokenService;
    }

    /**
     * Show the application home page.
     *
     * @return \Illuminate\View\View
     */
    public function __invoke()
    {
        $token = $this->tokenService->hasOauthTokens() ? FetchTokenService::call() : '';

        return $this->responder->withPayload($token)->respond();
    }
}
