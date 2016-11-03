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
     * @param OpenWeatherMap $client
     */
    public function __construct(OpenWeatherMap $client)
    {
        $this->client  = $client;
    }

    /**
     * @param string $query
     * @param string $unit
     * @param string $language
     *
     * @return string
     */
    public function getWeather($query, $unit, $language)
    {
        $displayedUnit = $unit === 'metric' ? 
            $this->unit = 'C' : 
            $this->unit = 'F'
        ;

        try {
            $result   = $this->client->getWeather($query, $unit, $language);
            $response = sprintf(
                '*%s* has *%s* and a temperature of *%dº%s*', 
                $result->city->name,
                $result->weather->description,
                $result->temperature->now->getValue(),
                $this->unit
            );
        } catch(OWMException $e) {
            $response = 'Sorry, I can\'t find that place';
        } catch(\Exception $e) {
            $response = 'Unknown Error';
        }
        return $response;
    }
}

