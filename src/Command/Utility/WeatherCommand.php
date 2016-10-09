<?php

namespace Eve\Command\Utility;

use Eve\Message;
use Eve\WeatherClient;
use Eve\Command\ClientCommand;

final class WeatherCommand extends ClientCommand
{
    /**
     * @var WeatherClient
     */
    private $weatherClient;

    /**
     * @param WeatherClient $weatherClient
     */
    public function setWeatherClient(WeatherClient $weatherClient): self
    {
        $this->weatherClient = $weatherClient;

        return $this;
    }

    /**
     * @param Message $message
     *
     * @return bool
     */
    public function canHandle(Message $message): bool
    {
        return preg_match('/\b(weather)\b/', $message->text());
    }

    /**
     * @param Message $message
     */
    public function handle(Message $message)
    {
        if (!$this->weatherClient) {
            return;
        }

        $matches = [];
        $content = '> No weather data found';

        preg_match_all('/weather (.*)/', $message->text(), $matches);

        if ($matches[1]) {
            $result = $this->weatherClient->getWeatherFor($matches[1][0]);
            $info   = json_decode($result, true);

            if (!empty($info['name'])) {
                $content = sprintf(
                    '>Today\'s weather for *%s* today will be *%s*',
                    $info['name'],
                    $info['weather'][0]['main']
                );
            }
        }

        $this->client->sendMessage(
            $content,
            $message->channel()
        );
    }
}
