<?php

namespace App\Handlers\Moderation;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;

final class WarnHandler extends Handler
{
    /**
     * {@inheritdoc}
     */
    public function canHandle(Event $event)
    {
        return 
            $event->isMessage() && 
            ($event->isDirectMessage() || $event->mentions($this->eve->userId())) &&
            $event->matches('/warn ./i')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        var_dump($this->eve->userIsAdmin($event->sender()));

        $this->send(
            Message::saying('Check debug output')
            ->to($event->sender())
            ->privately()
        );
    }
}
