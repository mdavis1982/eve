<?php

namespace Eve\Command;

use GuzzleHttp\Client;

class GiphyClient
{
    private $client;
    private $api_key;

    public function __construct()
    {
        $this->api_key = getenv('GIPHY_TOKEN');

        $this->client = new Client([
            'base_uri' => 'http://api.giphy.com/v1/gifs/translate'
        ]);
    }

    public function getImageFor(string $search)
    {
        $request = $this->client->request('GET', '?api_key=' . $this->api_key . '&s=' . $search . '&limit=1');

        return $request->getBody();
    }
}
