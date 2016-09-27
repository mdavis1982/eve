<?php

namespace Eve;

use Eve\Command\CommandCollection;
use Eve\Command\PingCommand;
use Eve\Command\PunCommand;
use Eve\Command\SandwichCommand;
use Eve\Command\SlapCommand;
use Eve\Command\ThanksCommand;
use Eve\Loader\JsonLoader;
use React\EventLoop\Factory;
use Slack\Payload;
use Slack\User;

final class Eve
{
    const DATA_DIRECTORY = __DIR__ . '/../data/';

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
        $client->connect()->then(
            function () use ($client) {
                echo 'Connected' . PHP_EOL;

                $client->getAuthedUser()->then(
                    function (User $user) use ($client) {
                        $client->setUserId($user->getId());
                    }
                )
                ;
            }
        )
        ;
    }

    /**
     * @param SlackClient $client
     */
    private function prepareMessageHandler(SlackClient $client)
    {
        $commandCollection = $this->makeCommandCollection($client);

        $client->on(
            'message',
            function (Payload $data) use ($client, $commandCollection) {
                $message = new Message($data->getData());

                // Only handle messages sent to the bot
                if ($message->isDm() || false !== stripos($message->text(), "<@{$client->userId()}>")) {
                    $commandCollection->handle($message);
                }
            }
        );
    }

    /**
     * @param SlackClient $client
     *
     * @return CommandCollection
     */
    private function makeCommandCollection(SlackClient $client): CommandCollection
    {
        return CommandCollection::make()
            ->push(PingCommand::create($client))
            ->push(SandwichCommand::create($client))
            ->push(SlapCommand::create($client))
            ->push(PunCommand::create($client)->setLoader(new JsonLoader(self::DATA_DIRECTORY . 'puns.json')))
            ->push(ThanksCommand::create($client)->setLoader(new JsonLoader(self::DATA_DIRECTORY . 'thank-you.json')))
        ;
    }

    /**
     * @return Eve
     */
    public static function create()
    {
        return new self();
    }
}
