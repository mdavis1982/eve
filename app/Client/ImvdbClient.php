<?php

namespace App\Client;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

final class ImvdbClient
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
     * @var string
     */
    private $apiKey;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client  = $client;
        $this->baseUrl = "http://imvdb.com/api/v1";
    }

    /**
     * @param string $query
     *
     * @return string|null
     */
    public function musicFor($query)
    {
        $searchVideoId = json_decode(
            $this->client->get(sprintf(
                "{$this->baseUrl}/search/videos?q=%s",
                urlencode($query)
            ))->getBody(), 
            true
        );

        $videoId = $searchVideoId['results'] ? $searchVideoId['results'][0]['id'] : null;

        if(is_null($videoId)){
           return "Couldn't find any results for that search."; 
        }

        $searchVideoUrl = json_decode(
            $this->client->get(sprintf(
                "{$this->baseUrl}/video/{$videoId}?include=sources",
                urlencode($query)
            ))->getBody(), 
            true
        );

        $link = null;
        
        if($searchVideoUrl['sources'][0] && $searchVideoUrl['sources'][0]['source'] == "youtube"){
            Log::debug("youtube");
            $link = $this->constructYoutubeLink($searchVideoUrl['sources'][0]);
        }

        if($searchVideoUrl['sources'][0] && $searchVideoUrl['sources'][0]['source'] == "vimeo"){
            Log::debug("vimeo");
            $link = $this->constructVimeoLink($searchVideoUrl['sources'][0]);
        }

        if(!$searchVideoUrl['sources'][0]){
            return "I could not find a music video for that query.";
        }

        return  "Here it is! {$link}";
    }

    private function constructYoutubeLink($results)
    {
        return "https://www.youtube.com/watch?v={$results['source_data']}";
    }

    private function constructVimeoLink($results)
    {
        return "https://vimeo.com/{$results['source_data']}";
    }
}
