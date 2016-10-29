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

        $rates     = $this->data['rates'];
        $arguments = $this->arguments($event);
        $content   = '`@eve convert 10 GBP to USD`';
        $result    = '';
        $baseRate  = 1;

        if ($arguments->count() != 4) {
            return;
        }

        $args = [
            'conversionAmount'  => $arguments[0],
            'primaryCurrency'   => strtoupper($arguments[1]),
            'conversionText'    => strtolower($arguments[2]),
            'secondaryCurrency' => $this->getCurrencyFromArg($arguments[3]),
        ];

        if ($this->validArguments($args)) {

            if (! array_key_exists($args['primaryCurrency'], $rates) && ! array_key_exists($args['secondaryCurrency'], $rates)) {
                return;
            }

            $result = $this->getConversionResult($args, $rates, $baseRate);

            $content = sprintf(
                "<@%s> %s%s is around %s%s",
                $event->sender(),
                $this->symbolForCurrency($args['primaryCurrency']),
                $args['conversionAmount'],
                $this->symbolForCurrency($args['secondaryCurrency']),
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
     * @param  string $arg
     *
     * @return string
     */
    private function getCurrencyFromArg($arg)
    {
        return ($arg == '$') ? 'USD' : ($arg == '£') ? 'GBP' : strtoupper($arg);
    }

    /**
     * @param  array $args
     * @param  integer $rates
     * @param  integer $baseRate
     *
     * @return double
     */
    private function getConversionResult($args, $rates, $baseRate)
    {
        $secondArgNotExist = array_key_exists($args['primaryCurrency'], $rates) && ! array_key_exists($args['secondaryCurrency'], $rates);

        if ($secondArgNotExist && $rates[$args['primaryCurrency']] > $baseRate) {
            return number_format($args['conversionAmount'] * $rates[$args['primaryCurrency']], 2);
        }

        if ($secondArgNotExist) {
            return number_format($args['conversionAmount'] / $rates[$args['primaryCurrency']], 2);
        }

        if ($rates[$args['secondaryCurrency']] > $baseRate) {
            return number_format($args['conversionAmount'] / $rates[$args['secondaryCurrency']], 2);
        }

        return number_format($args['conversionAmount'] * $rates[$args['secondaryCurrency']], 2);
    }

    /**
     * @param  array $args
     *
     * @return boolean
     */
    private function validArguments($args) {
        return
            preg_match('/[0-9]/', $args['conversionAmount']) &&
            preg_match('/[a-zA-Z]{3}/', $args['primaryCurrency']) &&
            trim($args['conversionText'] == 'to') &&
            preg_match('/[a-zA-Z]{3}/', $args['secondaryCurrency'])
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
