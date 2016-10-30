<?php

namespace App\Client;

use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;

final class WeatherClient
{
    /**
     * @var OpenWeatherMap
     */
    private $client;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $unit;

    /**
     * @param $apiKey
     * @internal param OpenWeatherMap $client
     */
    public function __construct($apiKey)
    {
        $this->client  = new OpenWeatherMap($apiKey);
        $this->apiKey  = $apiKey;
    }

    /**
     * @param string $query
     * @param string $unit
     * @param string $language
     *
     * @return null|string
     */
    public function getWeather($query, $unit, $language)
    {
        $unit == 'metric' ? $this->unit = 'C' : $this->unit = 'F';

        try {
            $result = $this->client->getWeather($query, $unit, $language);
            $response = '*'.$result->city->name.'* has *'.$result->weather->description.
                            '* and a temperature of *'.$result->temperature->now->getValue().$this->unit.'*';
        } catch(OWMException $e) {
            $response = "Sorry, I can't find that place";
        } catch(\Exception $e) {
            $response = "Unknown Error";
        }
        return $response;
    }

}
