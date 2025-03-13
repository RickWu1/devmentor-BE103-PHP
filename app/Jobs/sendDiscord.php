<?php

namespace App\Jobs;

use App\Notifications\DiscordNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\Discord\DiscordChannel;

class sendDiscord implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $user;
    protected $event;

    public function __construct($user, $event)
    {
        $this->user  = $user;
        $this-> event = $event;
    }

    public function handle(): void
    {

        Log::info("SendDiscord Job 執行成功，User ID: {$this->user->id}, Event ID: {$this->event->id}");

        $discordWebhookUrl = config('services.discord.webhook');

        if (! $discordWebhookUrl) {
            Log::error(' Discord Webhook URL 未設定，請檢查 .env 設定');
            return;
        }

        try {
            Notification::route(DiscordChannel::class, $discordWebhookUrl)
                ->notify(new DiscordNotification($this->user, $this->event));

            Log::info(" Discord notification sent successfully for user ID: {$this->user->id}");
        } catch (\Exception $e) {
            Log::error(' Failed to send Discord notification: ' . $e->getMessage());
        }
    }
}
