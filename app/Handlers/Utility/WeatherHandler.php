<?php

namespace App\Handlers\Utility;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;
use App\Client\WeatherClient;
use Illuminate\Support\Collection;

final class WeatherHandler extends Handler
{
    /**
     * @var WeatherClient
     */
    private $client;

    /**
     * @param WeatherClient $client
     */
    public function __construct(WeatherClient $client)
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
            $event->matches('/weather .+/i')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        $parameters = $this->getParameters($event);
        $query      = $this->getQuery($parameters);

        $message = $query ?
            $this->client->getCurrent($query) :
            'Please provide me with a location. Like `weather chicago`'
        ;

        $this->send(
            Message::saying($message)
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
        return explode(' ', substr($event->text(), strpos($event->text(), 'weather ') + 8));
    }

    /**
     * @param array $parameters
     *
     * @return string|null
     */
    private function getQuery(array $parameters)
    {
        $query = new Collection();

        collect($parameters)->each(function ($parameter) use (&$query) {
            $query->push($parameter);
        });

        return $query->implode(' ') ?: null;
    }
}
