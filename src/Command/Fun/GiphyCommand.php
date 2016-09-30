<?php

namespace Eve\Command\Fun;

use Eve\Command\ClientCommand;
use Eve\Command\GiphyClient;
use Eve\Message;

final class GiphyCommand extends ClientCommand
{
    /**
     * @param Message $message
     *
     * @return bool
     */
    public function canHandle(Message $message): bool
    {
        return preg_match('/\b(giphy)\b/', $message->text());
    }

    /**
     * @param Message $message
     */
    public function handle(Message $message)
    {
        $client = new GiphyClient();

        $matches = [];

        preg_match_all('/giphy (.*)/', $message->text(), $matches);

        $result = $client->getImageFor($matches[1][0]);

        if (isset($result)) {
            $info    = json_decode($result, true);

            $content = ">" . $info['data']['images']['downsized']['url'];
        } else {
            $content = "> *No giphy found*";
        }

        $this->client->sendMessage(
            $content,
            $message->channel()
        );
    }
}
