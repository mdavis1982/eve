<?php

namespace Eve;

use GuzzleHttp\Client;

final class XkcdClient
{
    const QUERY_STRING = '?text=%s';

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $search
     *
     * @return string
     */
    public function getResultFor(string $search): string
    {
        $response = $this->client->request(
            'POST',
            sprintf(self::QUERY_STRING, $search)
        );

        return $response->getBody();
    }
}
