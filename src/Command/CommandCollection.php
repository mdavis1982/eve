<?php

namespace Eve\Command;

use Eve\Message;
use Illuminate\Support\Collection;

final class CommandCollection extends Collection
{
    /**
     * @param Message $message
     */
    public function handle(Message $message)
    {
        $this->commandFor($message)->handle($message);
    }

    /**
     * @param Message $message
     *
     * @return Command
     */
    private function commandFor(Message $message)
    {
        return $this->filter(function (Command $command) use ($message) {
            return $command->canHandle($message);
        })->first(null, new NullCommand());
    }
}
