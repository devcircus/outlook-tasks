<?php

namespace App\Http\Actions\Home;

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Http\Responders\Home\IndexResponder;

class Index extends Action
{
    /** @var \App\Http\Responders\Home\IndexResponder */
    private $responder;

    /**
     * Construct a new Home Index action.
     *
     * @param  \App\Http\Responders\Home\IndexResponder  $responder
     */
    public function __construct(IndexResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Show the application home page.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\View\View
     */
    public function __invoke(Request $request)
    {
        $token = $request->user()->hasOauthTokens() ? $request->user()->fetchOauthToken() : '';

        $graph = new Graph();
        $graph->setAccessToken($token);

        $user = $graph->createRequest('GET', '/me')
                      ->setReturnType(Model\User::class)
                      ->execute();

        $messageQueryParams = [
            "\$select" => "subject,receivedDateTime,from",
            "\$orderby" => "receivedDateTime DESC",
            "\$top" => "10"
        ];

        $getMessagesUrl = '/me/mailfolders/inbox/messages?'.http_build_query($messageQueryParams);
        $messages = $graph->createRequest('GET', $getMessagesUrl)
                          ->setReturnType(Model\Message::class)
                          ->execute();

        return $this->responder->withPayload([
            'token' => $token,
            'displayName' => $user->getDisplayName(),
            'messages' => $messages,
        ])->respond();
    }
}
