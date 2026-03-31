<?php

namespace App\Shared\Infrastructure\Slack;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SlackNotification extends Notification
{
    use Queueable;

    protected $channel;
    protected $senderName;
    protected $content;

    public function __construct(string $content)
    {
        $this->setDefaultConfig();
        $this->content = $content;
    }

    private function setDefaultConfig()
    {
        $this->channel = config('slack.channel');
        $this->senderName = config('slack.name');
    }

    public function via($notifiable): array
    {
        return ['slack'];
    }

    public function toArray($notifiable): array
    {
        return [];
    }

    public function toSlack($notifiable): SlackMessage
    {
        return (new SlackMessage())
            ->from($this->senderName)
            ->to($this->channel)
            ->content($this->content);
    }
}
