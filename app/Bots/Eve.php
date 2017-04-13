<?php

namespace App\Bots;

use Slack\User;
use Slack\Payload;
use App\Slack\Event;
use App\Slack\Message;
use Slack\RealTimeClient;
use React\EventLoop\Factory;
use App\Handlers\HandlerManager;
use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;

final class Eve
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var HandlerManager
     */
    private $handlerManager;

    /**
     * @var RealTimeClient
     */
    private $client;

    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @param User
     */
    private $me;

    /**
     * @param string $token
     */
    public function __construct($token, HandlerManager $handlerManager)
    {
        $this->token          = $token;
        $this->handlerManager = $handlerManager;

        $this->handlerManager->setEve($this);

        $this->initialiseClient();
    }

    private function initialiseClient()
    {
        $this->loop   = Factory::create();
        $this->client = new RealTimeClient($this->loop);

        $this->client->setToken($this->token);

        $this->client->on(
            'message',
            function (Payload $data) {
                $this->handlerManager->handle(Event::withPayload($data));
            }
        );
    }

    /**
     * @return PromiseInterface
     */
    public function connect()
    {
        return $this->client->connect()->then(function () {
            $this->client->getAuthedUser()->then(function (User $user) {
                $this->me = $user;
            });
        });
    }

    public function run()
    {
        $this->loop->run();
    }

    /**
     * @param Message $message
     */
    public function send(Message $message)
    {
        if ($message->isPrivate()) {
            return $this->sendPrivateMessage($message);
        }

        return $this
            ->client
            ->getChannelGroupOrDMByID($message->channel())
            ->then(function ($channel) use ($message) {
                // Only mention the recipient if it isn't a Direct Message
                $mention = ($message->recipient() && $channel->getId()[0] !== 'D') ? "<@{$message->recipient()}> " : '';

                $this->client->send($mention . $message->content(), $channel);
            })
        ;
    }

    /**
     * @param Message $message
     */
    private function sendPrivateMessage(Message $message)
    {
        return $this
            ->client
            ->getUserById($message->recipient())
            ->then(function ($user) use ($message) {
                $this->client->getDMByUser($user)->then(function ($channel) use ($message) {
                    $this->client->send($message->content(), $channel);
                });
            })
        ;
    }

    /**
     * @return string
     */
    public function userId()
    {
        return isset($this->me) ? $this->me->getId() : '';
    }

    /**
     * @param string $userId
     *
     * @return bool
     */
    public function userIsAdmin($userId)
    {
        $isAdmin = false;

        $this->client->getUserById($userId)->done(function ($user) use (&$isAdmin) {
            $isAdmin = $user->isAdmin();
        });

        return $isAdmin;
    }
}
