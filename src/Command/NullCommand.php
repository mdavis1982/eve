<?php

namespace Eve\Command;

use Eve\Message;

/**
 * NullCommand
 */
class NullCommand extends Command
{
    /**
     * @param Message $message
     *
     * @return bool
     */
    public function canHandle(Message $message): bool
    {
        return true;
    }

    /**
     * @param Message $message
     */
    public function handle(Message $message)
    {
        ; // Do what Matthew does all day
    }
}
