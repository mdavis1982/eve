<?php

namespace App\Handlers\Media;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;
use App\Client\GiphyClient;

final class GiphyHandler extends Handler
{
    /**
     * @var GiphyClient
     */
    private $client;

    /**
     * @param GiphyClient $client
     */
    public function __construct(GiphyClient $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function canHandle(Event $event)
    {
        return
            $event->isMessage() &&
            ($event->isDirectMessage() || $event->mentions($this->eve->userId())) &&
            $event->matches('/giphy .+/i')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        $query = substr($event->text(), strpos($event->text(), 'giphy ') + 6);

        $content = '> ' . ($this->client->imageFor($query) ?: 'No Giphy Found');

        $this->send(
            Message::saying($content)
            ->inChannel($event->channel())
        );
    }
}
