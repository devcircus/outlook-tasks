<?php

namespace App\Http\Actions\Email;

use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Services\Email\DeleteAllEmailService;
use App\Http\Responders\Email\DeleteAllEmailResponder;

class DeleteAllEmail extends Action
{
    /** @var \App\Http\Responders\Email\DeleteAllEmailResponder */
    private $responder;

    /**
    * Construct a new DeleteAllEmail action.
    *
    * @param  \App\Http\Responders\Email\DeleteAllEmailResponder  $responder
    */
    public function __construct(DeleteAllEmailResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        DeleteAllEmailService::call($request->user());

        return $this->responder->respond();
    }
}
