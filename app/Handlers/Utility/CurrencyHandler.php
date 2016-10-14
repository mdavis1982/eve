<?php

namespace App\Handlers\Utility;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;
use App\Loader\LoadsData;
use App\Loader\JsonLoader;
use Illuminate\Support\Collection;

final class CurrencyHandler extends Handler
{
    use LoadsData;

    protected $dataFile = 'currency.json';


    /**
     * @param Calculator $calculator
     */
    public function __construct(JsonLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * {@inheritdoc}
     */
    public function canHandle(JsonLoader $loader, Event $event)
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
        $arguments = $this->arguments($event);

        $this->send(
            Message::saying('`conversion in progress' . $this->joinArguments($arguments) . '`')
            ->inChannel($event->channel())
            ->to($event->sender())
        );
    }

    /**
     * @param Event $event
     *
     * @return Collection
     */
    private function arguments(Event $event)
    {
        preg_match_all(
            '/([\w]+)/',
            substr($event->text(), strpos($event->text(), 'currency ') + 9),
            $matches
        );

        return collect($matches[0])->unique();
    }


    /**
     * @param Collection $arguments
     *
     * @return string
     */
    private function joinArguments(Collection $arguments)
    {
        $last = $arguments->pop();

        if (! $arguments->isEmpty()) {
            return $arguments->implode(', ') . ' and ' . $last;
        }

        return $last;
    }
}
