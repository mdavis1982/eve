<?php

namespace App\Handlers\Reference;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;
use Jleagle\Imdb\Exceptions\ImdbException;
use Jleagle\Imdb\Imdb;

final class ImdbHandler extends Handler
{
    /**
     * @var Imdb
     */
    private $imdb;

    /**
     * @param Imdb $imdb
     */
    public function __construct(Imdb $imdb)
    {
        $this->imdb = $imdb;
    }

    /**
     * {@inheritdoc}
     */
    public function canHandle(Event $event)
    {
        return
            $event->isMessage() &&
            ($event->isDirectMessage() || $event->mentions($this->eve->userId())) &&
            $event->matches('/imdb .+/i')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        $query = substr($event->text(), strpos($event->text(), 'imdb ') + 5);

        try {
            $results = $this->imdb->search($query);
            $message = 'http://imdb.com/title/'.$results[0]->imdbId;
        } catch(ImdbException $e) {
            $message = "Movie not found";
        }

        $this->send(
            Message::saying($message)
                ->inChannel($event->channel())
                ->to($event->sender())
        );
    }
}
