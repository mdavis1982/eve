<?php

namespace App\Handlers\Utility;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;

final class CurrencyHandler extends Handler
{
    /**
     * {@inheritdoc}
     */
    public function canHandle(Event $event)
    {
        return
            $event->isMessage() &&
            ($event->isDirectMessage() || $event->mentions($this->eve->userId())) &&
            $event->matches('/currency .+/i')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        $converter = new \CurrencyConverter\Converters\FixerIO();


        $this->send(
            Message::saying('`conversion in progress`')
            ->inChannel($event->channel())
            ->to($event->sender())
        );
    }
}
