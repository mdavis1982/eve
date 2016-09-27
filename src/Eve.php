<?php

namespace Eve;

use Slack\User;
use Eve\Message;
use React\EventLoop\Factory;
use Eve\Command\CommandManager;

class Eve
{
    public function run()
    {
        // Setup
        $eventLoop = Factory::create();

        $client = new SlackClient($eventLoop);
        $client->setToken(getenv('SLACK_TOKEN'));

        // Connect to Slack
        $this->prepareMessageHandler($client);
        $this->connect($client);

        // Run!
        $eventLoop->run();
    }

    private function connect(SlackClient $client)
    {
        $client->connect()->then(function() use ($client) {
            echo 'Connected' . PHP_EOL;

            $client->getAuthedUser()->then(function (User $user) use ($client) {
                $client->setUserId($user->getId());
            });
        });
    }

    private function prepareMessageHandler(SlackClient $client)
    {
        $manager = $this->makeManager($client);

        $client->on('message', function ($data) use ($client, $manager) {
            $message = new Message($data->getData());

            // Only handle messages sent to the bot
            if ($message->isDm() || false !== stripos($message->text(), "<@{$client->userId()}>")) {
                $manager->handle($message);
            }
        });
    }

    private function makeManager(SlackClient $client): CommandManager
    {
        $manager = new CommandManager($client);
        $manager->addCommand(\Eve\Command\PingCommand::class);
        $manager->addCommand(\Eve\Command\SandwichCommand::class);
        $manager->addCommand(\Eve\Command\SlapCommand::class);
        $manager->addCommand(\Eve\Command\PunCommand::class);

        return $manager;
    }
}
