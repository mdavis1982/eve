<?php

namespace Eve;

use GuzzleHttp\Client;

final class WeatherClient
{
    const QUERY_STRING = '?q=%s&appid=%s';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @param Client $client
     * @param string $apiKey
     */
    public function __construct(Client $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $search
     *
     * @return string
     */
    public function getWeatherFor(string $search): string
    {
        $response = $this->client->request(
            'GET',
            sprintf(self::QUERY_STRING, $search, $this->apiKey)
        );

        return $response->getBody();
    }
}
