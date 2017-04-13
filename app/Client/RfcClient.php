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
            $url      = "https://tools.ietf.org/html/{$id}";
            $response = $this->client->head($url, [
                'verify'          => false,
                'allow_redirects' => false,
            ]);

            switch ($response->getStatusCode()) {
                case 200:
                    return $url;
                case 302:
                    return $response->getHeaderLine('Location');
            }
        } catch (BadResponseException $error) {
            // fall through
        }

        return null;
    }
}
