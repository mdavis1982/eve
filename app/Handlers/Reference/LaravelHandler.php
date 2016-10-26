<?php

namespace App\Handlers\Reference;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

final class LaravelHandler extends Handler
{
    /**
     * @var Client
     */
    private $client;

    const VERSION_LATEST = '5.3';

    private static $versions = [
        'master', '5.3', '5.2', '5.1', '5.0', '4.2'
    ];

    /**
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
            $event->matches('/laravel .+/i')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        $words = explode(' ',$event->text());

        if(preg_match('/\<@.*?\>/', $words[0]))
            array_splice($words, 0, 1);

        $version = self::VERSION_LATEST;
        $query = null;

        if(isset($words[1]))
            in_array($words[1], self::$versions) ? $version = $words[1] : $query = $words[1];

        if(isset($words[2]))
            in_array($words[2], self::$versions) ? $version = $words[2] : $query = $words[2];

        try {
            $query = urlencode($query);
            $url = "https://laravel.com/docs/$version/$query";
            $this->client->head($url);
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

