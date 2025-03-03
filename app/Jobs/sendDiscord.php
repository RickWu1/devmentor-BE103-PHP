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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $event;

    public function __construct($user, $event)
    {
        $this->user  = $user;
        $this->event = $event;
    }

    public function handle(): void
    {

        // Log::info('Running sendDiscord job');

        // dd($this->user, $this->event);

        Log::info("SendDiscord Job 執行成功，User ID: {$this->user->id}, Event ID: {$this->event->id}");

        $discordWebhookUrl = "https://discordapp.com/api/webhooks/1311613486136164422/26vIg6uKUhVKPh_lOFnehVLLItJckQPLEolJUJFxfPzAV1I-E9bsudmCM2uLkWrJliQy";

        if (! $discordWebhookUrl) {
            Log::error("❌ Discord Webhook URL 未設定，請檢查 .env 設定");
            return;
        }

        try {
            Notification::route(DiscordChannel::class, $discordWebhookUrl)
                ->notify(new DiscordNotification($this->user, $this->event));

            Log::info("✅ Discord notification sent successfully for user ID: {$this->user->id}");
        } catch (\Exception $e) {
            Log::error("❌ Failed to send Discord notification: " . $e->getMessage());
        }
    }
}
