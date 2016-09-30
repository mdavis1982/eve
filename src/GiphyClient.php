<?php

namespace Eve\Command;

use GuzzleHttp\Client;

class GiphyClient
{
    private $client;

    public function __construct()
    {
    }

    public function getImageFor(string $search, string $key)
    {
        $this->client = new Client([
            'base_uri' => 'http://api.giphy.com/v1/gifs/'
        ]);

        $request = $this->client->request(
            'GET',
            'translate?s=' . $search . '&api_key=' . $key . '&limit=1'
        );

        return $request->getBody();
    }
}
