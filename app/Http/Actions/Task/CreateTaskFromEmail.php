<?php

namespace App\Http\Actions\Task;

use App\Models\Email;
use Inertia\Response;
use PerfectOblivion\Actions\Action;
use App\Http\Responders\Task\CreateTaskFromEmailResponder;

class CreateTaskFromEmail extends Action
{
    /** @var \App\Http\Responders\Task\CreateTaskFromEmailResponder */
    private $responder;

    /**
     * Construct a new CreateTask action.
     *
     * @param  \App\Http\Responders\Task\CreateTaskFromEmailResponder  $responder
     */
    public function __construct(CreateTaskFromEmailResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \App\Models\Email  $email
     */
    public function __invoke(Email $email): Response
    {
        dump($email);
        $email->load(['category']);
        dump($email);

        return $this->responder->withPayload([
            'title' => $email->subject,
            'description' => $email->body,
            'report_to' => $email->from_name,
            'email_id' => $email->id,
        ])->respond();
    }
}
