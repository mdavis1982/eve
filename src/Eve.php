<?php

namespace Eve;

use GuzzleHttp\Client as HttpClient;
use Slack\User;
use Eve\Command;
use Slack\Payload;
use Eve\Loader\JsonLoader;
use React\EventLoop\Factory;
use Eve\Command\CommandCollection;

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
                );
            }
        );
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
        $giphyClient = new GiphyClient(
            new HttpClient(['base_uri' => getenv('GIPHY_URI')]),
            getenv('GIPHY_TOKEN')
        );

        return CommandCollection::make()
            ->push(Command\Fun\GiphyCommand::create($client)->setGiphyClient($giphyClient))
            ->push(Command\Fun\SlapCommand::create($client))
            ->push(Command\Fun\SandwichCommand::create($client))
            ->push(Command\Utility\PingCommand::create($client))
            ->push(Command\Fun\PunCommand::create($client)->setLoader(new JsonLoader(self::DATA_DIRECTORY . 'puns.json')))
            ->push(Command\Conversation\HelloCommand::create($client)->setLoader(new JsonLoader(self::DATA_DIRECTORY . 'hello.json')))
            ->push(Command\Conversation\ThanksCommand::create($client)->setLoader(new JsonLoader(self::DATA_DIRECTORY . 'thank-you.json')))
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
