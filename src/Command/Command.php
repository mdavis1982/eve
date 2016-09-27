<?php

namespace Eve\Command;

use Eve\Message;

/**
 * Command
 */
interface Command
{
    /**
     * @param Message $message
     *
     * @return bool
     */
    public function canHandle(Message $message): bool;

    /**
     * @param Message $message
     */
    public function handle(Message $message);
}
