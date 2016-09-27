<?php

namespace Eve\Command;

use Eve\Message;
use Eve\SlackClient;

/**
 * CommandManager
 */
class CommandManager
{
    /**
     * @var CommandCollection
     */
    private $commands;

    /**
     * CommandManager constructor.
     *
     * @param SlackClient $client
     */
    public function __construct(SlackClient $client)
    {
        $this->client = $client;

        $this->commands = new CommandCollection();
    }

    /**
     * @param string $command
     *
     * @return CommandManager
     */
    public function addCommand(string $command)
    {
        $this->commands->push(new $command($this->client));

        return $this;
    }

    /**
     * @param Message $message
     */
    public function handle(Message $message)
    {
        $this->commands->commandFor($message)->handle($message);
    }
}
