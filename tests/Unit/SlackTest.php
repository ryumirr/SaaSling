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
        $message = 'Hello, Slack! I\'m a test message';

        $this->mock(SlackNotificationService::class, function ($mock) use ($message) {
            $mock->shouldReceive('send')
                ->once()
                ->with($message);
        });

        app(SlackNotificationService::class)->send($message);
    }
}
