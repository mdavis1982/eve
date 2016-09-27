<?php

namespace Eve\Command;

use Eve\Message;
use Eve\SlackClient;

/**
 * Command
 */
abstract class Command
{
    /**
     * Command constructor.
     *
     * @param SlackClient|null $client
     */
    public function __construct(SlackClient $client = null)
    {
        $this->client = $client;
    }

    /**
     * @param Message $message
     *
     * @return bool
     */
    abstract public function canHandle(Message $message): bool;

    /**
     * @param Message $message
     */
    abstract public function handle(Message $message);
}
