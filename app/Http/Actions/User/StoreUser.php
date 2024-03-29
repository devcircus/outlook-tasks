<?php

namespace App\Http\Actions\User;

use App\Http\DTO\User;
use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Services\User\StoreUserService;
use App\Http\Responders\User\StoreUserResponder;

class StoreUser extends Action
{
    /** @var \App\Http\Responders\User\StoreUserResponder */
    private $responder;

    /**
     * Construct a new StoreUser action.
     *
     * @param  \App\Http\Responders\User\StoreUserResponder  $responder
     */
    public function __construct(StoreUserResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Store a mew user from the provided data.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illluminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $created = StoreUserService::call(User::fromRequest($request));

        return $this->responder->withPayload($created)->respond();
    }
}
