<?php

namespace Eve\Command;

use Eve\Message;

final class NullCommand implements Command
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
