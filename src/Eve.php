<?php

namespace Eve;

use Slack\User;
use Slack\Payload;
use React\EventLoop\Factory;
use Eve\Command\CommandManager;

final class Eve
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

    /**
     * @param SlackClient $client
     */
    private function connect(SlackClient $client)
    {
        $client->connect()->then(function() use ($client) {
            echo 'Connected' . PHP_EOL;

            $client->getAuthedUser()->then(function (User $user) use ($client) {
                $client->setUserId($user->getId());
            });
        });
    }

    /**
     * @param SlackClient $client
     */
    private function prepareMessageHandler(SlackClient $client)
    {
        $manager = $this->makeManager($client);

        $client->on('message', function (Payload $data) use ($client, $manager) {
            $message = new Message($data->getData());

            // Only handle messages sent to the bot
            if ($message->isDm() || false !== stripos($message->text(), "<@{$client->userId()}>")) {
                $manager->handle($message);
            }
        });
    }

    /**
     * @param SlackClient $client
     *
     * @return CommandManager
     */
    private function makeManager(SlackClient $client): CommandManager
    {
        $manager = CommandManager::create($client)
            ->addCommand(Command\PingCommand::class)
            ->addCommand(Command\SandwichCommand::class)
            ->addCommand(Command\SlapCommand::class)
            ->addCommand(Command\PunCommand::class)
            ->addCommand(Command\ThanksCommand::class)
        ;

        return $manager;
    }

    /**
     * @return Eve
     */
    public static function create()
    {
        return new self();
    }
}
