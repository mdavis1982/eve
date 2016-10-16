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
        $content   = '`@eve convert 10 GPB to USD`';
        $baseRate  = 1;

        if ($arguments->count() == 4 && $this->validArguments($arguments)) {
            $rates   = $this->data['rates'];

            if (! array_key_exists($arguments[1], $rates) || ! array_key_exists($arguments[3], $rates)) {
                return;
            }

            $result  = round($arguments[0] * $rates[strtoupper($arguments[3])], 2);
            $content = sprintf(
                "<@%s> %s%s is around %s%s",
                $event->sender(),
                $this->symbol($arguments[1]),
                strtoupper($arguments[0]),
                $this->symbol($arguments[3]),
                strtoupper($result)
            );
        }

        $this->send(
            Message::saying($content)
            ->inChannel($event->channel())
            ->to($event->sender())
        );
    }

    /**
     * @param  $arguments
     *
     * @return boolean
     */
    private function validArguments($arguments) {
        return
            preg_match('/[A-Z]{3}/', $arguments[1]) &&
            trim($arguments[2] == 'to') &&
            preg_match('/[A-Z]{3}/', $arguments[3])
        ;
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
            '/([\w]+)/',
            substr($event->text(), strpos($event->text(), 'convert ') + 8),
            $matches
        );

        return collect($matches[0])->unique();
    }
}
