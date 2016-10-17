<?php

namespace App\Client;

use GuzzleHttp\Client;

final class FixerClient
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
     * @param Client $client
     */
    public function __construct(Client $client, $baseUrl)
    {
        $this->client  = $client;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $query
     *
     * @return string|null
     */
    public function rates($query)
    {
        $response = json_decode(
            $this->client->get(sprintf(
                "{$this->baseUrl}?base=%s",
                urlencode($query)
            ))->getBody(),
            true
        );

        return $response;
    }
}
