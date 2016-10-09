<?php

namespace Eve\Command\Fun;

use Eve\Message;
use Eve\GiphyClient;
use Eve\Command\ClientCommand;

final class GiphyCommand extends ClientCommand
{
    /**
     * @var GiphyClient
     */
    private $giphyClient;

    /**
     * @param GiphyClient $giphyClient
     */
    public function setGiphyClient(GiphyClient $giphyClient): self
    {
        $this->giphyClient = $giphyClient;

        return $this;
    }

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
        if (!$this->giphyClient) {
            return;
        }

        $matches = [];
        $content = '> No giphy found';

        preg_match_all('/giphy (.*)/', $message->text(), $matches);

        if ($matches[1]) {
            $result = $this->giphyClient->getImageFor($matches[1][0]);
            $info   = json_decode($result, true);

            if (!empty($info['data'])) {
                $content = '>' . $info['data']['images']['downsized']['url'];
            }
        }

        $this->client->sendMessage(
            $content,
            $message->channel()
        );
    }
}
