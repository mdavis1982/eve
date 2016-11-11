<?php

namespace App\Handlers\Media;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;
use App\Client\ImvdbClient;

final class ImvdbHandler extends Handler
{
    /**
     * @var Imvdb
     */
    private $imvdb;

    /**
     * @param Imvdb $imvdb
     */
    public function __construct(ImvdbClient $imvdb)
    {
        $this->imvdb = $imvdb;
    }

    /**
     * {@inheritdoc}
     */
    public function canHandle(Event $event)
    {
        return
            $event->isMessage() &&
            ($event->isDirectMessage() || $event->mentions($this->eve->userId())) &&
            $event->matches('/music .+/i')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        $query = substr($event->text(), strpos($event->text(), 'music ') + 5);

        $message = $this->imvdb->musicFor($query);

        $this->send(
            Message::saying($message)
                ->inChannel($event->channel())
                ->to($event->sender())
        );
    }
}
