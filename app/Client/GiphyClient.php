<?php

namespace App\Client;

use GuzzleHttp\Client;

final class GiphyClient
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
     * @return string|null
     */
    public function imageFor($query)
    {
        $response = json_decode(
            $this->client->get(sprintf(
                "{$this->baseUrl}?api_key=%s&s=%s&limit=1&rating=pg-13",
                $this->apiKey,
                urlencode($query)
            ))->getBody(),
            true
        );

        return $response['data'] ? $response['data']['images']['downsized']['url'] : null;
    }
}
