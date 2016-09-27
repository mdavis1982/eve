<?php

namespace Eve\Command;

use Eve\Message;
use Eve\SlackClient;

/**
 * PingCommand
 */
final class PingCommand implements Command
{
    /**
     * @var SlackClient
     */
    private $client;

    /**
     * Command constructor.
     *
     * @param SlackClient $client
     */
    public function __construct(SlackClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param Message $message
     *
     * @return bool
     */
    public function canHandle(Message $message): bool
    {
        return false !== stripos($message->text(), 'ping');
    }

    /**
     * @param Message $message
     */
    public function handle(Message $message)
    {
        $messagePrefix = $message->isDm() ? '' : "<@{$message->user()}>: ";

        $this->client->sendMessage(
            "{$messagePrefix}Pong!",
            $message->channel()
        );
    }
}
