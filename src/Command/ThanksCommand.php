<?php

namespace Eve\Command;

use Eve\Message;
use Eve\SlackClient;

final class ThanksCommand implements Command
{
    const PHRASES = [
        "You're welcome!",
        'No worries',
        'Sure thing',
        'No problemo!',
        'No sweat!',
    ];

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
