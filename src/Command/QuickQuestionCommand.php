<?php

namespace Eve\Command;

use Slack\User;
use Eve\Message;
use Slack\Channel;
use Eve\SlackClient;
use Slack\ChannelInterface;

class QuickQuestionCommand extends Command
{
    public function canHandle(Message $message): bool
    {
        return !$message->isDm() && false !== stripos($message->text(), 'question:');
    }

    public function handle(Message $message)
    {
        $query    = implode('+', explode(' ', substr($message, strpos($message, 'question:'))));
        $result   = file_get_contents('https://www.google.co.uk/search?q=' . $query);

        mb_convert_encoding($result, 'UTF-8', mb_detect_encoding($result, 'UTF-8, ISO-8859-1', true));

        $start   = strpos($result, '<div class="_sPg">');

        if (!$start) {
            $content = 'I have no idea what you\'re talking about.';
        } else {
            $content = strip_tags(substr($result, $start, strpos($result, '</div>', $start) - $start));
        }

        $this->client->sendMessage(
            $content,
            $message->channel()
        );
    }
}






