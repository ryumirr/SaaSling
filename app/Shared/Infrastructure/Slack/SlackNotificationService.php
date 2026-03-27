<?php

namespace App\Shared\Infrastructure\Slack;

use Illuminate\Notifications\Notifiable;
use App\Shared\Contracts\SlackNotificationServiceInterface;

class SlackNotificationService implements SlackNotificationServiceInterface
{
    use Notifiable;

    public function getKey(): string
    {
        return 'slack-notifier';
    }

    public function send(string $content): void
    {
        $this->notify(new SlackNotification($content));
    }

    protected function routeNotificationForSlack($notification = null)
    {
        return config('slack.webhook_url');
    }
}
