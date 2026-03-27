<?php

namespace App\Shared\Infrastructure\Slack;

use Illuminate\Notifications\Notifiable;
use App\Shared\Contracts\SlackNotificationServiceInterface;

class SlackNotificationService implements SlackNotificationServiceInterface
{
    use Notifiable;

    public function send($content): void
    {
        $this->notify(new SlackNotification($content));
    }

    protected function routeNotificationForSlack()
    {
        return config('slack.webhook_url');
    }
}
