<?php
namespace App\Jobs;

use App\Notifications\DiscordNotification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Notification;
use NotificationChannels\Discord\DiscordChannel;

class SendDiscord
{
    use SerializesModels;

    protected $user;
    protected $event;

    public function __construct($user, $event)
    {
        $this->user  = $user;
        $this->event = $event;
    }

    public function handle(): void
    {

        $discordWebhookUrl = config('services.discord.webhook_url');

        try {
            Notification::route(DiscordChannel::class, $discordWebhookUrl)
                ->notify(new DiscordNotification($this->user, $this->event));

            Log::info("Discord notification sent successfully for user ID: {$this->userId}");
        } catch (\Exception $e) {
            Log::error("Failed to send Discord notification");
        }
    }
}
