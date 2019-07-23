<?php

namespace App\Http\Actions\Pdf;

use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Services\Pdf\ShowTaskListPdfService;
use App\Http\Responders\Pdf\ShowTaskListPdfResponder;

class ShowTaskListPdf extends Action
{
    /** @var \App\Http\Responders\Pdf\ShowTaskListPdfResponder */
    private $responder;

    /**
    * Construct a new ShowTaskListPdf action.
    *
    * @param  \App\Http\Responders\Pdf\ShowTaskListPdfResponder  $responder
    */
    public function __construct(ShowTaskListPdfResponder $responder)
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
        $data = ShowTaskListPdfService::call($request->user(), $request->route('type'));

        return $this->responder->withPayload($data);
    }
}
