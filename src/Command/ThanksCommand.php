<?php

namespace Eve\Command;

use Eve\Message;

final class ThanksCommand extends ClientCommand
{
    const PHRASES = [
        "You're welcome!",
        'No worries',
        'Sure thing',
        'No problemo!',
        'No sweat!',
    ];

    /**
     * @param Message $message
     *
     * @return bool
     */
    public function canHandle(Message $message): bool
    {
        return preg_match('/\b(thanks)\b/', $message->text());
    }

    /**
     * @param Message $message
     */
    public function handle(Message $message)
    {
        $messagePrefix = $message->isDm() ? '' : "<@{$message->user()}>: ";

        $content = collect(self::PHRASES)->random();

        $this->client->sendMessage(
            "{$messagePrefix}{$content}",
            $message->channel()
        );
    }
}
