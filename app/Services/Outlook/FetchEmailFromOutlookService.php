<?php

namespace App\Services\Outlook;

use App\Models\User;
use App\Models\Email;
use Microsoft\Graph\Graph;
use Carbon\CarbonImmutable;
use App\Outlook\Query\QueryParameter;
use PerfectOblivion\Services\Traits\SelfCallingService;
use Microsoft\Graph\Http\GraphResponse;

class FetchEmailFromOutlookService
{
    use SelfCallingService;

    /** @var \Microsoft\Graph\Graph */
    private $graph;

    /** @var \App\Outlook\Query\QueryParameter */
    private $queryParameter;

    /** @var \App\Models\Email */
    private $emails;

    /**
     * Construct a new FetchEmailFromOutlookService.
     *
     * @param  \Microsoft\Graph\Graph  $graph
     * @param  \App\Outlook\Query\QueryParameter  $queryParameter
     * @param  \App\Models\Email  $emails
     */
    public function __construct(Graph $graph, QueryParameter $queryParameter, Email $emails)
    {
        $this->graph = $graph;
        $this->queryParameter = $queryParameter;
        $this->emails = $emails;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function run(User $user)
    {
        $this->graph->setAccessToken($user->fetchOauthToken());

        return $this->syncMailForUser($user);
    }

    /**
     * Sync email from Outlook with database.
     *
     * @param  \App\Models\User  $user
     */
    private function syncMailForUser(User $user)
    {
        $mostRecentEmail = $user->emails()->latest('received_at')->first();

        // Once we have retrieved the latest synced email from the database, we will use that date
        // to query Outlook. If more than 1 email exists with that same received datetime, we
        // need to decide which ones don't exist in the database and store them. Then we
        // can query Outlook for all emails received AFTER that date and be assured
        // that we haven't skipped or duplicated any emails in the local db.

        if ($mostRecentEmail) {
            $mostRecentReceivedDate = $mostRecentEmail->received_at;
            $response = $this->fetchAllEmailsReceivedAt($mostRecentReceivedDate);
            $count = $response->getBody()['@odata.count'];

            if ($count > 1) {
                foreach ($response->getBody()['value'] as $message) {
                    if ($message['subject'] !== $mostRecentEmail->subject && $message['from']['emailAddress']['address'] !== $mostRecentEmail->from_address) {
                        $this->emails->storeEmailFromOutlookForUser($message, $user);
                    }
                }
            }

            $response = $this->fetchAllEmailsAfterDate($mostRecentReceivedDate);

            foreach ($response->getBody()['value'] as $message) {
                $this->emails->storeEmailFromOutlookForUser($message, $user);
            }
        } else {
            $startDate = now()->subDays(config('outlook.days_back', 10) + 1);
            $response = $this->fetchAllEmailsAfterDate($startDate);

            foreach ($response->getBody()['value'] as $message) {
                $this->emails->storeEmailFromOutlookForUser($message, $user);
            }
        }
    }

    /**
     * Fetch all emails from Outlook, received at the given datetime.
     *
     * @param  \Carbon\CarbonImmutable  $date
     */
    private function fetchAllEmailsReceivedAt(CarbonImmutable $date): GraphResponse
    {
        $this->queryParameter->set('filter', "receivedDateTime eq {$date->toIso8601ZuluString()}");

        $listMessagesUrl = '/me/mailfolders/inbox/messages?'.http_build_query($this->queryParameter->get());

        return $this->graph->createRequest('GET', $listMessagesUrl)
            ->addHeaders(['Prefer' => 'outlook.body-content-type="text"'])->execute();
    }

    /**
     * Fetch all Outlook emails received after the given date for the given user.
     *
     * @param  \Carbon\CarbonImmutable  $date
     */
    private function fetchAllEmailsAfterDate(CarbonImmutable $date): GraphResponse
    {
        $this->queryParameter->set('filter', "receivedDateTime ge {$date->addSeconds(1)->toIso8601ZuluString()}");
        $this->queryParameter->set('select', 'subject');

        $listMessagesUrl = '/me/mailfolders/inbox/messages?'.http_build_query($this->queryParameter->get());

        $forCount = $this->graph->createRequest('GET', $listMessagesUrl)
            ->execute();

        $total = $forCount->getBody()['@odata.count'];

        $this->queryParameter->set('filter', "receivedDateTime ge {$date->addSeconds(1)->toIso8601ZuluString()}");
        $this->queryParameter->set('top', $total);
        $this->queryParameter->reset('select');

        $listMessagesUrl = '/me/mailfolders/inbox/messages?'.http_build_query($this->queryParameter->get());

        return $this->graph->createRequest('GET', $listMessagesUrl)
            ->addHeaders(['Prefer' => 'outlook.body-content-type="text"'])
            ->execute();
    }
}
