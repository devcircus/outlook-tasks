<?php

namespace App\Http\Actions\Pdf;

use App\Models\Task;
use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Services\Pdf\ShowTaskPdfService;
use App\Http\Responders\Pdf\ShowTaskPdfResponder;

class ShowTaskPdf extends Action
{
    /** @var \App\Http\Responders\Pdf\ShowTaskPdfResponder */
    private $responder;

    /**
    * Construct a new ShowTaskPdf action.
    *
    * @param  \App\Http\Responders\Pdf\ShowTaskPdfResponder  $responder
    */
    public function __construct(ShowTaskPdfResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \App\Models\Task  $task
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Task $task)
    {
        return $this->responder->withPayload($task);
    }
}
