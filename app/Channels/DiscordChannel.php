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
        $data = $notification->toDiscord($notifiable);
        $discordWebhookUrl = config('services.discord.webhook');

        Http::post($discordWebhookUrl, $data);
    }
}
