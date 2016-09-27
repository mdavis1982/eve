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
    public function __construct(SlackClient $client)
    {
        $this->client = $client;
    }
}
