<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\User;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use PerfectOblivion\Services\Traits\SelfCallingService;

class EmailTaskService
{
    use SelfCallingService;

    /** @var \Microsoft\Graph\Graph */
    private $graph;

    /**
     * Construct a new FetchEmailFromOutlookService.
     *
     * @param  \Microsoft\Graph\Graph  $graph
     */
    public function __construct(Graph $graph)
    {
        $this->graph = $graph;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\Task  $task
     * @param  array  $to
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function run(Task $task, array $to, User $user)
    {
        $this->graph->setAccessToken($user->fetchOauthToken());

        $this->sendEmail($this->createEmailFromTask($task, $to));
    }

    /**
     * Send a task to the given address via email.
     *
     * @param  \App\Models\Task  $task
     * @param  array  $to
     */
    private function createEmailFromTask(Task $task, array $to): Model\Message
    {
        $me = $this->graph->createRequest('GET', '/me')
                            ->setReturnType(Model\User::class)
                            ->execute();

        $subject = $task->title;

        $message = new Model\Message();
        $message->setSubject('TODO: '.$subject);
        $body = new Model\ItemBody();
        $body->setContent($task->description."\n".'Notes:'."\n".$task->notes);
        $message->setBody($body);
        $message->setToRecipients($this->buildRecipientList($to));

        return $message;
    }

    /**
     * Build the array of recipients.
     *
     * @param  array  $to
     */
    private function buildRecipientList(array $to): array
    {
        $recipients = [];

        foreach ($to as $address) {
            $emailAddress = new Model\EmailAddress();
            $emailAddress->setAddress($address);
            $recipient = new Model\Recipient();
            $recipient->setEmailAddress($emailAddress);
            $recipients[] = $recipient;
        }

        return $recipients;
    }

    /**
     * Send a task to the given address via email.
     *
     * @return mixed
     */
    private function sendEmail(Model\Message $message)
    {
        $body = ['message' => $message];
        $this->graph->createRequest('POST', '/me/sendmail')
                      ->attachBody($body)
                      ->execute();
    }
}
