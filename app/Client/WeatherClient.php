<?php

namespace App\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

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
        try {
            $response = json_decode(
                $this->client->get(sprintf(
                    "{$this->baseUrl}/current.json?key=%s&q=%s",
                    $this->apiKey,
                    urlencode($query)
                ))->getBody(),
                true
            );

            return $this->buildCurrentResponse($response);
        } catch (BadResponseException $e) {
            $response = json_decode(
                $e->getResponse()->getBody()->getContents(),
                true
            );

            return $response['error']['message'];
        }

        return "Unknown error occurred";
    }

    /**
     * @param array $response
     * @return string
     */
    private function buildCurrentResponse(array $response)
    {
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
