<?php

namespace App\Handlers\Reference;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;
use App\Client\RfcClient;

final class RfcHandler extends Handler
{
    /**
     * @var RfcClient
     */
    private $client;

    /**
     * @param RfcClient $client
     */
    public function __construct(RfcClient $client)
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
            $event->matches('/\b(rfc)\b/i')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        $id       = $this->getRfcId($event->text());
        $response = 'You have to provide a numeric RFC id, so I can give you the link.';

        if ($id) {
            $url      = $this->client->getById($id);
            $response = $url ? $url : "I could not find `RFC {$id}`...";
        }

        $this->send(
            Message::saying($response)
                ->inChannel($event->channel())
                ->to($event->sender())
        );
    }

    /**
     * Extract the numeric RFC id out of the query string.
     *
     * @param  string $query
     * @return string
     */
    private function getRfcId($query)
    {
        return filter_var(substr($query, strpos($query, 'rfc ') + 4), FILTER_SANITIZE_NUMBER_INT);
    }
}
