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
        //Grab the name of the user sending the message
        $messagePrefix = $message->isDm() ? '' : "<@{$message->user()}>: ";
        
        //set the string for the search url
        $google = 'https://www.google.com/#q=';
        
        //get all text after google
        $string = $message->text();
        $prefix = 'google';
        
        $index = strpos($string, $prefix) + strlen($prefix);
        
        $str = substr($string, $index);
        
        //remove the first + symbol
        $str = urlencode(substr($str, 1));
        
        $content = $google . str_replace(' ', '+', $str);
        
        $this->client->sendMessage(
            "{$messagePrefix}{$content}",
            $message->channel()
        );
    }
}
