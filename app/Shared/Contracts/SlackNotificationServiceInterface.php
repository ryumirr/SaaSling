<?php

namespace App\Shared\Contracts;

interface SlackNotificationServiceInterface
{
    /**
     * Slackメッセージ送信
     *
     * @param  string $content
     * @return void
     */
    public function send(string $content): void;
}
