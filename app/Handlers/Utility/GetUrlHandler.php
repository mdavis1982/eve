<?php
namespace App\Handlers\Utility;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;
use Illuminate\Support\Collection;
use App\Client\GithubClient;
use App\Client\PackagistClient;

final class GetFileUrlHandler extends Handler{
    
    /**
     * @var GitHubClient
     */
    private $gitHubclient;

    /**
     * @var PackagistClient
     */
    private $packagistClient;

    /**
     * @param GitHubClient $gitHubClient
     * @param PackagistClient $packagistClient
     */
    public function __construct(GitHubClient $gitHubclient, PackagistClient $packagistClient){
        $this->gitHubclient = $gitHubclient;
        $this->packagistClient = $packagistClient;
    }

    /**
     * {@inheritdoc}
     */
    public function canHandle(Event $event)
    {
        return
            $event->isMessage() &&
            ($event->isDirectMessage() || $event->mentions($this->eve->userId())) &&
            $event->matches('/\b(link me)\b/i');
    }

    /**
     * Return an URL of packagist or/and GiTHub depending on what is asked
     * @param $event
     */
    public function handle(Event $event)
    {
        $variables  = $this->getNames($event);
        $vendor = (isset($variables[0])) ? $variables[0] : null;
        $packageName = (isset($variables[1])) ? $variables[1] : null;
        $fileName = (isset($variables[2])) ? $variables[2] : null;

        if($fileName == null && $packageName != null){
           $gitHubUrl = $this->gitHubclient->linkForRepo($vendor, $packageName);
           $packagistUrl = $this->packagistClient->linkForPackage($vendor, $packageName);
           $message = implode("\n", [$gitHubUrl, $packagistUrl]);
        }else if($packageName == null){
            $message = implode("\n", $this->packagistClient->search($vendor));
        }else{
            $message = $this->gitHubclient->linkForNamespace($vendor, $packageName, $fileName);    
        }

        $this->send(
            Message::saying($message)
            ->inChannel($event->channel())
            ->to($event->sender())
        );
    }
    /**
     * Get the variables from the provided event.
     *
     * @param  Event $event
     * @return string[]
     */
    protected function getNames(Event $event)
    {
        preg_match_all("/(?<=link me |(?<!^)\G\\\\)\w+/", $event->text(), $match);
        return $match[0];
    }
}