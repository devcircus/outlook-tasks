<?php

namespace App\Http\Actions\Admin;

use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Services\Admin\IndexService;
use App\Http\Responders\Admin\IndexResponder;

class Index extends Action
{
    /** @var \App\Http\Responders\Admin\IndexResponder */
    private $responder;

    /**
    * Construct a new Admin Index action.
    *
    * @param  \App\Http\Responders\Admin\IndexResponder  $responder
    */
    public function __construct(IndexResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $data = IndexService::call();

        return $this->responder->withPayload($data)->respond();
    }
}
