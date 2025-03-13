<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class DiscordChannel
{
    /**
     * 發送通知到 Discord
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // 從通知中獲取 Discord 發送的數據
        $data = $notification->toDiscord($notifiable);

        // 從 .env 文件中獲取 Webhook URL
        $discordWebhookUrl = config('services.discord.webhook');

        // 發送 HTTP 請求到 Discord Webhook URL
        Http::post($discordWebhookUrl, $data);
    }
}
