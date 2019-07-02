<?php

namespace App\Http\Actions\Outlook;

use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Services\Outlook\SyncEmailService;
use App\Http\Responders\Outlook\SyncEmailResponder;

class SyncEmail extends Action
{
    /** @var \App\Http\Responders\Outlook\SyncEmailResponder */
    private $responder;

    /**
    * Construct a new SyncEmail action.
    *
    * @param  \App\Http\Responders\Outlook\SyncEmailResponder  $responder
    */
    public function __construct(SyncEmailResponder $responder)
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
        SyncEmailService::call($request->user());

        return $this->responder->respond();
    }
}
