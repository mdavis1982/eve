<?php

namespace App\Client;

use GuzzleHttp\Client;

final class WeatherClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @param Client $client
     */
    public function __construct(Client $client, $baseUrl, $apiKey)
    {
        $this->client  = $client;
        $this->baseUrl = $baseUrl;
        $this->apiKey  = $apiKey;
    }

    /**
     * @param string $query
     *
     * @return string
     */
    public function getCurrent($query)
    {
        $response = json_decode(
            $this->client->get(sprintf(
                "{$this->baseUrl}/current.json?key=%s&q=%s",
                $this->apiKey,
                urlencode($query)
            ))->getBody(),
            true
        );

        return $this->buildCurrentResponse($response);
    }

    /**
     * @param array $response
     * @return string
     */
    private function buildCurrentResponse(array $response)
    {
        if (array_key_exists('error', $response)) {
            return $response['error']['message'];
        }

        return sprintf(
            "Here is the current weather for *%s*\nLocation: *%s, %s*\nCondition: *%s*\nTemperature: *%dºC / %dF*\nLast Updated: %s",
            $response['location']['name'],
            $response['location']['region'],
            $response['location']['country'],
            $response['current']['condition']['text'],
            $response['current']['temp_c'],
            $response['current']['temp_f'],
            $response['current']['last_updated']
        );
    }
}
