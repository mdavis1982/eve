<?php

namespace App\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

final class RfcClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Try and fetch an RFC url, based on a RFC id.
     *
     * @param  string $id
     * @return string|null
     */
    public function getById($id)
    {
        try {
            $url = "https://tools.ietf.org/html/rfc{$id}";
            $response = $this->client->get($url, ['verify' => false]);

            if ($response->getStatusCode() == 200) {
                return $url;
            }
        } catch (BadResponseException $error) {
            // fall through
        }

        return null;
    }
}
