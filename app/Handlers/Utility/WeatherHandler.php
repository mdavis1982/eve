<?php

namespace App\Handlers\Utility;

use App\Client\WeatherClient;
use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;
use Illuminate\Support\Collection;

final class WeatherHandler extends Handler
{
    /**
     * @var WeatherClient
     */
    private $client;

    /**
     * @var array
     */
    private static $units = [
        'metric', 'imperial'
    ];

    const DEFAULT_UNIT = 'imperial';
    const DEFAULT_LANGUAGE = 'en';

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
        $unit       = $this->getUnit($parameters);

        $query ?
            $response = $this->client->getWeather($query, $unit, self::DEFAULT_LANGUAGE) :
            $response = 'Please provide me with a location. Like `weather chicago '.$unit.'`';

        $this->send(
            Message::saying($response)
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
     * @return string
     */
    private function getUnit(array $parameters)
    {
        $unit = self::DEFAULT_UNIT;
        collect($parameters)->each(function ($parameter) use (&$unit) {
            if (in_array($parameter, self::$units)) {
                $unit = $parameter;
            }
        });

        return $unit;
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
            if (!in_array($parameter, self::$units)) {
                $query->push($parameter);
            }
        });

        return $query->implode(' ') ?: null;
    }
}

