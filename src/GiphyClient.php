<?php

namespace Eve;

use GuzzleHttp\Client;

final class GiphyClient
{
    const QUERY_STRING = '?api_key=%s&s=%s&limit=1&rating=pg-13';

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
    public function getImageFor(string $search): string
    {
        $response = $this->client->request(
            'GET',
            sprintf(self::QUERY_STRING, $this->apiKey, $search)
        );

        return $response->getBody();
    }
}
