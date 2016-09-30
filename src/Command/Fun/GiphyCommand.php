<?php

namespace Eve\Command\Fun;

use Eve\Message;
use Eve\Command\ClientCommand;

use Eve\Command\GiphyClient;

final class GiphyCommand extends ClientCommand
{
    /**
     * @param Message $message
     *
     * @return bool
     */
    public function canHandle(Message $message): bool
    {
        return preg_match('/giphy .+/', $message->text());
    }

    /**
     * @param Message $message
     */
    public function handle(Message $message)
    {
        $client = new GiphyClient();

        $result = $client->getImageFor(
            str_replace(' ', '+', trim(substr($message->text(), 19))),
            getenv('GIPHY_TOKEN')
        );

        if (isset($result)) {
            $info    = json_decode($result, true);

            $content = ">>>" . $info['data']['images']['downsized']['url'];
        } else {
            $content = ">>> *No giphy found*";
        }

        $this->client->sendMessage(
            $content,
            $message->channel()
        );
    }
}
