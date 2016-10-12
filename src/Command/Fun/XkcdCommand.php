<?php

namespace Eve\Command\Fun;

use Eve\Message;
use Eve\XkcdClient;
use Eve\Command\ClientCommand;

final class XkcdCommand extends ClientCommand
{
    /**
     * @var XkcdClient
     */
    private $xkcdClient;

    /**
     * @param XkcdClient $giphyClient
     */
    public function setXkcdClient(XkcdClient $xkcdClient): self
    {
        $this->xkcdClient = $xkcdClient;

        return $this;
    }

    /**
     * @param Message $message
     *
     * @return bool
     */
    public function canHandle(Message $message): bool
    {
        return preg_match('/\b(xkcd )\b/', $message->text());
    }

    /**
     * @param Message $message
     */
    public function handle(Message $message)
    {
        if (!$this->xkcdClient) {
            return;
        }

        $matches = [];
        $content = '> I couldn\'t find a comic matching your search';

        preg_match_all('/xkcd (.*)/', $message->text(), $matches);

        if ($matches[1]) {
            if (!$matches[1][0]) {
                return;
            }

            $result = $this->xkcdClient->getResultFor(str_replace(' ', '-', $matches[1][0]));
            $info   = json_decode($result, true);

            if (!empty($info['response_type'])) {
                $content = '> ' . $info['attachments'][0]['image_url'];
            }
        }

        $this->client->sendMessage(
            $content,
            $message->channel()
        );
    }
}
