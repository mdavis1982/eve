<?php

namespace App\Client;

use GuzzleHttp\Client;

final class GithubClient
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
    public function __construct(Client $client)
    {
        $this->client  = $client;
        $this->baseUrl = "https://api.github.com/search/code?q=";
    }

    public function linkFor($vendor, $package, $file){
        $response = json_decode(
            $this->client->get(
                sprintf("{$this->baseUrl}filename:%s+repo:%s/%s", 
                    $file, 
                    $vendor, 
                    $package))->getBody(), 
            true);

        return $response['items'][0]['html_url'];

    }

}
