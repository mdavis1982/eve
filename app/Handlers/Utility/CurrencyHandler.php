<?php

namespace App\Handlers\Utility;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;
use App\Loader\LoadsData;
use App\Loader\JsonLoader;
use Symfony\Component\Intl\Intl;
use Illuminate\Support\Collection;

final class CurrencyHandler extends Handler
{
    use LoadsData;

    protected $dataFile = 'currency.json';

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
            ($event->isDirectMessage() || $event->mentions($this->eve->userId())) &&
            $event->matches('/convert .+/i')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        $this->loadData();

        $arguments = $this->arguments($event);
        $content   = '`@eve convert 10 GPB to USD` or `@eve convert £10 to $`';

        // convert 10 GBP to USD
        if ($arguments->count() == 4) {
            $rates   = $this->data['rates'];
            $result  = round($arguments[0] * $rates[strtoupper($arguments[3])], 2);
            $symbol  = Intl::getCurrencyBundle()->getCurrencySymbol('USD');
            $content = "<@{$event->sender()}> " . $this->symbol($arguments[1]) . strtoupper($arguments[0]) . " is around " . $this->symbol($arguments[3]) . strtoupper($result);
        }

        // convert £10 to $
        if ($arguments->count() == 3) {
            $content = "<@{$event->sender()}> " . $arguments[0] . " is around ";
        }

        $this->send(
            Message::saying($content . json_encode($arguments))
            ->inChannel($event->channel())
            ->to($event->sender())
        );
    }

    /**
     * @param  string $currency
     *
     * @return string
     */
    private function symbol(string $currency)
    {
        return Intl::getCurrencyBundle()->getCurrencySymbol(strtoupper($currency));
    }

    /**
     * @param Event $event
     *
     * @return Collection
     */
    private function arguments(Event $event)
    {
        preg_match_all(
            '/\s+/',
            substr($event->text(), strpos($event->text(), 'convert ') + 8),
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
