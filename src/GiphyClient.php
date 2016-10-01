<?php

namespace Eve;

use GuzzleHttp\Client;

class GiphyClient
{
    private $client;
    private $api_key;

    public function __construct()
    {
        $this->api_key = getenv('GIPHY_TOKEN');

        $this->client = new Client([
            'base_uri' => getenv('GIPHY_URI')
        ]);
    }

    public function getImageFor(string $search): string
    {
        $response = $this->client->request('GET', '?api_key=' . $this->api_key . '&s=' . $search . '&limit=1');

        return $response->getBody();
    }
}
