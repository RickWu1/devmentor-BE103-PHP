<?php

namespace App\Jobs;

use App\Notifications\TelegramNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Notification;
use NotificationChannels\Telegram\TelegramChannel;

class SendTelegram implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $chatId = '7748237713'; // 替換成目標 Chat ID

        try {
            // 發送 Telegram 推播通知
            Notification::route(TelegramChannel::class, $chatId)
                ->notify(new TelegramNotification());

            // 執行成功後可以記錄日誌或進行其他操作
            echo "Telegram 推播通知已發送成功";
        } catch (\Exception $e) {
            // 捕捉例外並記錄錯誤日誌
            echo "Telegram 推播通知發送失敗";
        }
    }

}
