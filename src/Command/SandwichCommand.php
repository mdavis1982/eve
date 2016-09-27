<?php

namespace Eve\Command;

use Eve\Message;

final class SandwichCommand extends ClientCommand
{
    /**
     * @param Message $message
     *
     * @return bool
     */
    public function canHandle(Message $message): bool
    {
        return preg_match('/(sudo )?make me a sandwich/', $message->text());
    }

    /**
     * @param Message $message
     */
    public function handle(Message $message)
    {
        $messagePrefix = $message->isDm() ? '' : "<@{$message->user()}>: ";

        $content = false === stripos($message->text(), 'sudo') ?
            'No, make one yourself' :
            'User is not in the sudoers file. This incident will be reported.'
        ;

        $this->client->sendMessage(
            "{$messagePrefix}{$content}",
            $message->channel()
        );
    }
}
