<?php

namespace Eve\Command;

use Eve\Message;

/**
 * PingCommand
 */
class PingCommand extends Command
{
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
