<?php

namespace App\Handlers\Utility;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;

final class HelpHandler extends Handler
{
    /**
     * {@inheritdoc}
     */
    public function canHandle(Event $event)
    {
        return
            $event->isMessage() &&
            ($event->isDirectMessage() || $event->mentions($this->eve->userId())) &&
            $event->matches('/\b(help)\b/i')
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        $message = "Eve is a PHP Slack bot for the Larachat community, powered by community contributions. Send commands to Eve via mentioning or direct message. \n \n"
            . "Currently, Eve supports the following commands:  \n "
            . "`hello` - say hello to Eve `hello @eve` \n "
            . "`thanks` - send a thank you to Eve `Thanks @eve` \n"
            . "`ping` - ping Eve, get a pong back `ping @eve` \n "
            . "`pun` - get Eve to say a pun `@eve pun` :laughing: \n"
            . "`sandwich` - ask Eve to make you a sandwhich `@eve make me a sandwhich` :sandwich: \n"
            . "`slap` - tell Eve to slap someone for you`@eve slap @someone` \n"
            . "`eight ball` - see the future with eve `@eve 8-ball` :8ball: \n"
            . "`calculate` - run a calculation `@eve calculate 2x + 3y --x=2 --y=4` :nerd_face: \n"
            . "`giphy` - run giphy command `@eve giphy test`"
            . "`help` -show list of available commands \n"
            . " \n "
            . "Contributions are welcomed and encouraged. \n"
            . "GitHub: <https://github.com/mdavis1982/eve>";

        $this->send(
            Message::saying($message)
                ->to($event->sender())
                ->privately()
        );
    }
}


