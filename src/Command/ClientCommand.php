<?php

namespace Eve\Command;

use Eve\SlackClient;

abstract class ClientCommand implements Command
{
    /**
     * @var SlackClient
     */
    protected $client;

    /**
     * Command constructor.
     *
     * @param SlackClient $client
     */
    protected function __construct(SlackClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param SlackClient $client
     *
     * @return static
     */
    public static function create(SlackClient $client)
    {
        return new static($client);
    }
}
