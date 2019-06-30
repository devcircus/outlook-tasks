<?php

namespace App\Http\Actions\Email;

use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Services\Email\ListEmailsService;
use App\Http\Responders\Email\ListEmailsResponder;

class ListEmails extends Action
{
    /** @var \App\Http\Responders\Email\ListEmailResponder */
    private $responder;

    /**
    * Construct a new ListEmails action.
    *
    * @param  \App\Http\Responders\Email\ListEmailResponder  $responder
    */
    public function __construct(ListEmailResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $emails = ListEmailsService::call($request->user());

        return $this->responder->withPayload($emails)->respond();
    }
}
