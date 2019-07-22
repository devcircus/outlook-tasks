<?php

namespace App\Http\Responders\Pdf;

use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Str;
use PerfectOblivion\Responder\Responder;

class ShowTaskPdfResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        $pdf = resolve(PDF::class)->loadView('pdf.task', [
            'task' => $this->payload,
        ])->setPaper('letter', 'landscape');

        return response()->make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.Str::slug($this->payload->title).'"',
        ]);
    }
}
