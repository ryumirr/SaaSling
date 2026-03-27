<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Shared\Infrastructure\Slack\SlackNotificationService;

class SlackTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_slack_notification_service(): void
    {
        $message = 'Hello, Slack! \'m a test message';
        $SlackNotificationService = app(SlackNotificationService::class);
        $this->expectNotToPerformAssertions();
        $SlackNotificationService->send($message);
    }
}
