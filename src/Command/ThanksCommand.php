<?php

namespace Eve\Command;

use Eve\Loader\HasData;
use Eve\Message;
use Illuminate\Support\Collection;
use Eve\Loader\HasDataTrait;

final class ThanksCommand extends ClientCommand implements HasData
{
    use HasDataTrait;

    /**
     * @var Collection
     */
    private $data;

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
        $this->loadData();

        $messagePrefix = $message->isDm() ? '' : "<@{$message->user()}>: ";

        $content = collect($this->data)->random();

        $this->client->sendMessage(
            "{$messagePrefix}{$content}",
            $message->channel()
        );
    }
}
