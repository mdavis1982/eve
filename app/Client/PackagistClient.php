<?php

namespace App\Client;
use GuzzleHttp\Client;

final class PackagistClient
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
        $this->baseUrl = "https://packagist.org/search.json?q=";
    }

    public function linkFor($vendor="", $package=""){

        if($package == ""){
            $requestContent = "{$this->baseUrl}$vendor";    
        }else{
            $requestContent = "{$this->baseUrl}$vendor/$package";    
        }
        $response = json_decode($this->client->get($requestContent)->getBody(), true);      
        return $response['results'][0]['url'];
    }

}
