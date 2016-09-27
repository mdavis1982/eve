<?php

namespace Eve\Command;

use Eve\Message;
use Illuminate\Support\Collection;

final class CommandCollection extends Collection
{
    /**
     * @param Message $message
     *
     * @return Command
     */
    public function commandFor(Message $message)
    {
        return $this->filter(function (Command $command) use ($message) {
            return $command->canHandle($message);
        })->first(null, new NullCommand());
    }
}
