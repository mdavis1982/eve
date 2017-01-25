<?php

namespace App\Handlers\Reference;

use App\Slack\Event;
use App\Slack\Message;
use GuzzleHttp\Client;
use App\Handlers\Handler;
use GuzzleHttp\Exception\ClientException;

final class LaravelHandler extends Handler
{
    const LATEST_VERSION = '5.4';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private static $versions = [
        'master', '5.4', '5.3', '5.2', '5.1', '5.0', '4.2'
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
        $parameters = $this->getParameters($event);
        $version    = $this->getVersion($parameters);
        $query      = $this->getQuery($parameters);

        $reply = $url = sprintf('https://laravel.com/docs/%s/%s', $version, $query);

        try {
            $this->client->head($url);
        } catch (ClientException $e) {
            $reply = 'Could not find the documentation for *' . $query . '*';
        }

        $this->send(
            Message::saying($reply)
                ->inChannel($event->channel())
                ->to($event->sender())
        );
    }

    /**
     * @param Event $event
     *
     * @return array
     */
    private function getParameters(Event $event)
    {
        return explode(' ', substr($event->text(), strpos($event->text(), 'laravel ') + 8));
    }

    /**
     * @param array $parameters
     *
     * @return string
     */
    private function getVersion(array $parameters)
    {
        $version = self::LATEST_VERSION;

        collect($parameters)->each(function ($parameter) use (&$version) {
            if (in_array($parameter, self::$versions)) {
                $version = $parameter;
            }
        });

        return $version;
    }

    /**
     * @param array $parameters
     *
     * @return string|null
     */
    private function getQuery(array $parameters)
    {
        $query = null;

        collect($parameters)->each(function ($parameter) use (&$query) {
            if (!in_array($parameter, self::$versions)) {
                $query = $parameter;
            }
        });

        return $query;
    }
}

