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
     * @param string $baseCurrency
     *
     * @return string|null
     */
    public function rates($baseCurrency)
    {
        $response = json_decode(
            $this->client->get(sprintf(
                "{$this->baseUrl}?base=%s",
                urlencode($baseCurrency)
            ))->getBody(),
            true
        );

        return $response;
    }
}
