<?php

namespace Eve\Command\Utility;

use Eve\Message;
use Eve\Loader\HasData;
use Eve\Loader\HasDataTrait;
use Eve\Command\ClientCommand;

final class GoogleCommand extends ClientCommand implements HasData
{
    use HasDataTrait;

    /**
     * @param Message $message
     *
     * @return bool
     */
    public function canHandle(Message $message): bool
    {
        return !$message->isDm() && preg_match('/google .+/', $message->text());
    }

    /**
     * @param Message $message
     */
    public function handle(Message $message)
    {
        $this->loadData();

        $receiver = $this->receiver($message);

        $content = '';

        if ($message->text() == null) {            
            $content = "Add some keywords if you want me to search for you.\n";
        }
        
        $google = 'http://lmgtfy.com/?q=';

        $content .= '_' . $google . str_replace(' ', '+', $message->text()) . '_';

        $this->client->sendMessage(
            $content,
            $message->channel()
        );
    }

    /**
     * @param Message $message
     *
     * @return string
     */
    private function receiver(Message $message): string
    {
        preg_match('/slap ([<@]+)([\w]+)(>)/', $message->text(), $matches);

        return ($matches[1] ?? '') . ($matches[2] ?? '') . ($matches[3] ?? '');
    }
}
