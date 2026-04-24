<?php

namespace App\Libraries;

use Config\Services;

class TelegramLogs
{
    public static function send(string $message)
    {
        $token      = getenv('telegram.botToken');
        $chatId     = getenv('telegram.chatId');
        $url        = "https://api.telegram.org/bot{$token}/sendMessage";
        $client     = Services::curlrequest();

        $payload    = [
            'chat_id'    => $chatId,
            'text'       => $message,
            'parse_mode' => 'Markdown', // Agar bisa pakai bold/italic
        ];

        return $client->setForm($payload)->post($url);
    }
}