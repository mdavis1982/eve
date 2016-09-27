<?php

namespace Eve\Command;

use Slack\User;
use Eve\Message;
use Slack\Channel;
use Eve\SlackClient;
use Slack\ChannelInterface;

class PunCommand extends Command
{
    private $puns = [
        'I wondered why the baseball was getting bigger. Then it hit me.',
        'Did you hear about the guy whose whole left side was cut off? He\'s all right now.',
        'I wasn\'t originally going to get a brain transplant, but then I changed my mind.',
        'A friend of mine tried to annoy me with bird puns, but I soon realized that toucan play at that game.',
        'I\'d tell you a chemistry joke but I know I wouldn\'t get a reaction.',
        'I\'m reading a book about anti-gravity. It\'s impossible to put down.',
        'Did you hear about the guy who got hit in the head with a can of soda? He was lucky it was a soft drink.',
        'Yesterday I accidentally swallowed some food coloring. The doctor says I\'m OK, but I feel like I\'ve dyed a little inside.',
        'Have you ever tried to eat a clock? It\'s very time consuming.',
        'It\'s not that the man did not know how to juggle, he just didn\'t have the balls to do it.',
    ];

    public function canHandle(Message $message): bool
    {
        return preg_match('/\b(pun)\b/', $message->text());
    }
    
    public function handle(Message $message)
    {
        $messagePrefix = $message->isDm() ? '' : "<@{$message->user()}>: ";
        $content       = collect($this->puns)->random();
        
        $this->client->sendMessage(
            "{$messagePrefix}{$content}",
            $message->channel()
        );
    }
}
