<?php

return [
//Slack Auth Config
    'bot'         => env('SLACK_BOT', ''),
    'name'        => env('SLACK_SENDER_NAME', ''),
    'channel'     => env('SLACK_CHANNEL', ''),
    'webhook_url' => env('SLACK_WEBHOOK_URL', ''),
];
