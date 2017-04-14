<?php

namespace App\Client;

use GuzzleHttp\Client;

final class GitHubClient
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
        $this->baseUrl = "https://api.github.com/search/";
    }

    /**
     * Return a GitHub url for a namespace file, for example: Illuminate\Log\Writer
     * @param string $vendor
     * @param string $package
     * @param string $file
     *
     * @return string
     */
    public function linkForNamespace($vendor, $package, $file){
        $response = json_decode(
            $this->client->get(
                sprintf("{$this->baseUrl}code?q=filename:%s+repo:%s/%s", 
                    $file, 
                    $vendor, 
                    $package))->getBody(), 
            true);
        return $response['items'][0]['html_url'];
    }

    /**
     * Return a GitHub url for a package repo
     * @param string $vendor
     * @param string $package
     *
     * @return string
     */
    public function linkForRepo($vendor, $package){
        $response = json_decode(
            $this->client->get(
                sprintf("{$this->baseUrl}repositories?q=%s+user:%s", $package, $vendor))->getBody(), 
        true);
        return $response["items"][0]["html_url"];
    }

    /**
     * Search an string on github
     * @param string $term
     *
     * @return [string]
     */
    public function searchForRepo($term){
        $response = json_decode(
            $this->client->get(
                    sprintf("{$this->baseUrl}repositories?q=%s", $term)
                )->getBody(), 
        true);

        $results = $response['results'];
        $results_url = array_map(function($value){
            return $value['html_url'];
        }, $results);
        return $results_url;
    }



}
