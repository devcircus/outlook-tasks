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
     * @param  array  $data
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function run(array $data, User $user)
    {
        $this->graph->setAccessToken($user->fetchOauthToken());

        $this->sendEmail($this->createEmailFromData($data));
    }

    /**
     * Send a task to the given address via email.
     *
     * @param  \App\Models\Task  $task
     * @param  array  $to
     */
    private function createEmailFromData(array $data): Model\Message
    {
        $me = $this->graph->createRequest('GET', '/me')
                            ->setReturnType(Model\User::class)
                            ->execute();

        $subject = $data['subject'];

        $message = new Model\Message();
        $message->setSubject('TODO: '.$subject);
        $body = new Model\ItemBody();
        $body->setContent("Due date: {$data['due_date']}\r\n".$data['body']);
        $message->setBody($body);
        $message->setToRecipients($this->buildRecipientList($data['to']));

        return $message;
    }

    /**
     * Build the array of recipients.
     *
     * @param  array  $addresses
     */
    private function buildRecipientList(array $addresses): array
    {
        $recipients = [];

        foreach ($addresses as $address) {
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
        $this->graph->createRequest('POST', '/me/sendmail')
                      ->attachBody(['message' => $message])
                      ->execute();
    }
}
