<?php

namespace Eve\Command\Utility;

use Eve\Message;
use Eve\Loader\HasData;
use Eve\Loader\HasDataTrait;
use Eve\Command\ClientCommand;

final class GoogleCommand extends ClientCommand
{
    /**
     * @param Message $message
     *
     * @return bool
     */
    public function canHandle(Message $message): bool
    {
        return preg_match('/(sudo )?google/', $message->text());
    }

    /**
     * @param Message $message
     */
    public function handle(Message $message)
    {
        $messagePrefix = $message->isDm() ? '' : "<@{$message->user()}>: ";
        
        $google = 'https://www.google.com/#q=';
        
        //$content = '_' . $google . str_replace(' ', '+', $message->text()) . '_';
        
        $content = false === stripos($message->text(), 'sudo') ?
            '_' . $google . str_replace(' ', '+', $message->text()) . '_' :
            ''
        ;
        
        $this->client->sendMessage(
            "{$messagePrefix}{$content}",
            $message->channel()
        );
    }
}
