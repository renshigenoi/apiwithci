<?php

namespace App\Libraries;

use Config\Services;

class SlackLogs
{
    public static function send(string $message, string $type = 'info')
    {
        $webhookUrl = getenv('slack.webhook');
        $client     = Services::curlrequest();

        // Tentukan warna berdasarkan tipe
        $color = ($type === 'error') ? '#FF0000' : '#36a64f';

        $payload = [
            'attachments' => [
                [
                    'fallback' => 'Notification from CI4',
                    'color'    => $color,
                    'pretext'  => 'System Notification',
                    'title'    => 'App Event: ' . strtoupper($type),
                    'text'     => $message,
                    'footer'   => 'CI4 Slack Bot',
                    'ts'       => time()
                ]
            ]
        ];

        return $client->setBody(json_encode($payload))
                      ->setHeader('Content-Type', 'application/json')
                      ->post($webhookUrl);
    }
}