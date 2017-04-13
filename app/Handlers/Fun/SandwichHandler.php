<?php

namespace App\Handlers\Fun;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;

final class SandwichHandler extends Handler
{
    /**
     * {@inheritdoc}
     */
    public function canHandle(Event $event)
    {
        return
            $event->isMessage() &&
            ($event->isDirectMessage() || $event->mentions($this->eve->userId())) &&
            $event->matches('/\b(make me a sandwich)\b/i')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        $content = ! $event->matches('/\b(sudo )\b/i') ?
            'No. Make one yourself.' :
            '`User is not in the sudoers file. This incident will be reported.`'
        ;

        $this->send(
            Message::saying($content)
            ->inChannel($event->channel())
            ->to($event->sender())
        );
    }
}



