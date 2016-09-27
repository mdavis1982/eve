<?php

namespace Eve;

final class Message
{
    /**
     * Message constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->channel = $data['channel'] ?? null;
        $this->user    = $data['user'] ?? null;
        $this->text    = $data['text'] ?? null;
        $this->subtype = $data['subtype'] ?? null;
    }

    public function channel()
    {
        return $this->channel;
    }

    public function user()
    {
        return $this->user;
    }

    public function text()
    {
        return $this->text;
    }

    public function subtype()
    {
        return $this->subtype;
    }

    public function isDm(): bool
    {
        return $this->channel[0] === 'D';
    }
}
