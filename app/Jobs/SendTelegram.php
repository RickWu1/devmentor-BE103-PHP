<?php

namespace App\Jobs;

use App\Notifications\TelegramNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\Telegram\TelegramChannel;

class SendTelegram implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use InteractsWithQueue;
    use SerializesModels;

    protected $user;
    protected $event;

    public function __construct($user, $event)
    {
        $this->user = $user;
        $this->event = $event;
    }

    public function handle(): void
    {
        $chatId = config('services.telegram.chat_id');

        try {
            Notification::route(TelegramChannel::class, $chatId)
                ->notify(new TelegramNotification($this->user, $this->event));
            Log::info('Telegram 推播通知已發送成功，Chat ID: ' . $chatId);
        } catch (\Exception $e) {
            Log::error('Telegram 推播通知發送失敗: ' . $e->getMessage());
        }
    }
}
