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
        $this->baseUrl = "https://packagist.org/";
    }

    /**
     * Return a packagist url for the required package
     * @param string $vendor
     * @param string $package
     *
     * @return string
     */
    public function linkForPackage($vendor="", $package=""){
        return "{$this->baseUrl}packages/$vendor/$package";
    }

    /**
     * Return an array of packagist url for the requested string
     * @param string $package
     * 
     * @return [string]
     */
    public function search($package){
        $response = json_decode(
            $this->client->get(
                sprintf("{$this->baseUrl}search.json?q=%s", $package)
            )->getBody(),
        true);
        $results = $response['results'];
        $packagistUrls = array_map(function($value){
            return $value['url'];
        }, $results);
        return $packagistUrls;
    }

               

}
