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
        $message = <<<EOT
Eve is a PHP Slack bot for the Larachat community, powered by community contributions. 

Send commands to Eve via mentioning or direct message.

Currently, Eve supports the following commands:

```
hello      - say hello to Eve                    - hello @eve
thanks     - send a thank you to Eve             - thanks, @eve
ping       - ping Eve, get a pong back           - ping @eve
pun        - get Eve to say a pun                - @eve pun
sandwich   - ask Eve to make you a sandwich      - @eve make me a sandwhich
slap       - tell Eve to slap someone for you    - @eve slap @someone
eight ball - see the future with Eve             - @eve 8-ball
calculate  - run a calculation                   - @eve calculate 2x + 3y --x=2 --y=4
giphy      - get a random related GIF from Giphy - @eve giphy test
help       - show list of available commands     - @eve help
laravel    - search the laravel docs             - @eve laravel middleware
```

Contributions are welcomed and encouraged.

GitHub: https://github.com/mdavis1982/eve
EOT
        ;

        $this->send(
            Message::saying($message)
                ->to($event->sender())
                ->privately()
        );
    }
}
