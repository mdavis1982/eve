<?php

namespace App\Handlers\Reference;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

final class LaravelHandler extends Handler
{

    private $client;

    /**
     * Loads GuzzleHttp Client.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function canHandle(Event $event)
    {
        return
            $event->isMessage() &&
            ($event->isDirectMessage() || $event->mentions($this->eve->userId())) &&
            $event->matches('/\b(laravel)\b/i')
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        $versions = [
            'master', '5.3', '5.2', '5.1', '5.0', '4.2'
        ];

        $words = explode(' ',$event->text());

        if(in_array($words[2], $versions)) {
            $version = $words[2];
            $query = $words[3];
        } else {
            $version = $versions[1];
            $query = $words[2];
        }

        try {
            $url = "https://laravel.com/docs/$version/$query";
            $this->client->get($url);
            $reply = $url;
        } catch (ClientException $e) {
            $reply = 'Could not find the documentation for *'.$query.'*';
        }

        $this->send(
            Message::saying($reply)
                ->inChannel($event->channel())
                ->to($event->sender())
        );
    }
}

