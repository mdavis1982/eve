<?php

namespace App\Handlers\Moderation;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;
use App\Loader\LoadsData;
use App\Loader\JsonLoader;
use Illuminate\Support\Collection;

final class WarnHandler extends Handler
{
    use LoadsData;

    protected $dataFile = 'warnings.json';

    /**
     * @param JsonLoader $loader
     */
    public function __construct(JsonLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * {@inheritdoc}
     */
    public function canHandle(Event $event)
    {
        return
            $event->isMessage() &&
            $event->isDirectMessage() &&
            $this->eve->userIsAdmin($event->sender()) &&
            $event->matches('/warn ./i')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        $this->loadData();

        $receivers = $this->receivers($event);

        $receivers->each(function ($receiver) {
            $this->send(
                Message::saying($this->data->random())
                ->to($receiver)
                ->privately()
            );
        });

        $content = $receivers->isEmpty() ?
            'No users were warned' :
            'Warned ' . $this->joinReceivers($receivers)
        ;

        $this->send(
            Message::saying($content)
            ->to($event->sender())
            ->privately()
        );
    }

    /**
     * @param Event $event
     *
     * @return Collection
     */
    private function receivers(Event $event)
    {
        preg_match_all(
            '/<@([\w]+)>/',
            substr($event->text(), strpos($event->text(), 'warn ') + 5),
            $matches
        );

        return collect($matches[1])->unique();
    }

    /**
     * @param Collection $receivers
     *
     * @return string
     */
    private function joinReceivers(Collection $receivers)
    {
        $last = "<@{$receivers->pop()}>";

        if (! $receivers->isEmpty()) {
            return $receivers
                ->map(function ($receiver) {
                    return "<@{$receiver}>";
                })
                ->implode(', ') . ' and ' . $last;
        }

        return $last;
    }
}
