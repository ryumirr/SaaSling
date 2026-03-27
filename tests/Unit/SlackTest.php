<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Shared\Infrastructure\Slack\SlackNotificationService;
use Illuminate\Support\Facades\Notification;

class SlackTest extends TestCase
{
    /**
     * Ensure the Slack notification service dispatches a notification via Laravel's notification system.
     */
    public function test_slack_notification_service_dispatches_notification(): void
    {
        Notification::fake();

        $message = 'Hello, Slack! I\'m a test message';

        $service = app(SlackNotificationService::class);
        $service->send($message);

        Notification::assertSentOnDemandTimes(1);
    }
}
