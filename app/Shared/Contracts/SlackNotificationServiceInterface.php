<?php

namespace App\Shared\Contracts;

interface SlackNotificationServiceInterface
{
    /**
     * Slackメッセージ送信
     *
     * @param  mixed $message
     * @return void
     */
    public function send($content): void;
}
