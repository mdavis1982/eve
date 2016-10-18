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
        $baseRate  = 1;

        if ($arguments->count() == 4 && $this->validArguments($arguments)) {
            $rates  = $this->data['rates'];
            $result = '';

            if (! array_key_exists($arguments[1], $rates) && ! array_key_exists($arguments[3], $rates)) {
                return;
            }

            if (array_key_exists($arguments[1], $rates) && array_key_exists($arguments[3], $rates)) {
                $result = number_format($arguments[0] * $rates[strtoupper($arguments[3])], 2);
            }

            if (array_key_exists($arguments[1], $rates) && ! array_key_exists($arguments[3], $rates)) {
                if ($rates[strtoupper($arguments[1])] > $baseRate) {
                    $result = number_format($arguments[0] * $rates[strtoupper($arguments[1])], 2);
                } else {
                    $result = number_format($arguments[0] / $rates[strtoupper($arguments[1])], 2);
                }
            }

            if (! array_key_exists($arguments[1], $rates) && array_key_exists($arguments[3], $rates)) {
                if ($rates[strtoupper($arguments[3])] > $baseRate) {
                    $result = number_format($arguments[0] / $rates[strtoupper($arguments[3])], 2);
                } else {
                    $result = number_format($arguments[0] * $rates[strtoupper($arguments[3])], 2);
                }
            }

            $content = sprintf(
                "<@%s> %s%s is around %s%s",
                $event->sender(),
                $this->symbolForCurrency($arguments[1]),
                $arguments[0],
                $this->symbolForCurrency($arguments[3]),
                $result
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
    private function symbolForCurrency(string $currency)
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
