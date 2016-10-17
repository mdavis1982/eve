<?php

namespace App\Handlers\Utility;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;
use App\Loader\LoadsData;
use App\Loader\JsonLoader;
use Illuminate\Support\Collection;
use MathParser\StdMathParser as Calculator;
use MathParser\Exceptions\MathParserException;

final class CalculateHandler extends Handler
{
    use LoadsData;

    protected $dataFile = 'calculator.json';

    /**
     * @var Calculator
     */
    private $calculator;

    /**
     * @param JsonLoader $loader
     * @param Calculator $calculator
     */
    public function __construct(JsonLoader $loader, Calculator $calculator)
    {
        $this->loader     = $loader;
        $this->calculator = $calculator;
        $this->calculator->setSimplifying(false);
    }

    /**
     * {@inheritdoc}
     */
    public function canHandle(Event $event)
    {
        return
            $event->isMessage() &&
            ($event->isDirectMessage() || $event->mentions($this->eve->userId())) &&
            $event->matches('/\b(calculate)\b/i')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        $this->loadData();

        $expression = $this->getExpression($event);
        $variables  = $this->getVariables($event);

        $content = $expression
            ? 'That could have been better... Could you try again with another calculation?'
            : 'I need a calculation to work with...';

        if ($expression) {
            try {
                $outcome = $this->calculator->parse($expression)->evaluate($variables);
                $query   = $variables ? ' where `' . http_build_query($variables, '', ', ') . '`' : '';
                $content = "`{$expression} = {$outcome}`{$query}";
            } catch (MathParserException $error) {
                $errorName = class_basename($error);

                if ($this->data->has($errorName)) {
                    $content = collect($this->data->get($errorName))->random();
                }
            }
        }

        $this->send(
            Message::saying($content)
            ->inChannel($event->channel())
            ->to($event->sender())
        );
    }

    /**
     * Get the expression from the provided event
     *
     * @param  Event $event
     * @return string
     */
    protected function getExpression(Event $event): string
    {
        preg_match('/(?<=calculate)(.*?)(--[\w]+|$)/im', $event->text(), $match);

        return isset($match[1]) ? trim($match[1]) : '';
    }

    /**
     * Get the variables from the provided event.
     *
     * @param  Event $event
     * @return string[]
     */
    protected function getVariables(Event $event): array
    {
        preg_match_all('/--(\w+)[=: ](\w+)/i', $event->text(), $matches);

        if (isset($matches[1]) && isset($matches[2])) {
            return array_combine($matches[1], $matches[2]);
        }

        return [];
    }
}
