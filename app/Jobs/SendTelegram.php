<?php
namespace App\Jobs;

use App\Notifications\TelegramNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Notification;
use NotificationChannels\Telegram\TelegramChannel;

class SendTelegram implements ShouldQueue
{
    use SerializesModels;

    protected $user;
    protected $event;

    public function __construct($user, $event)
    {
        $this->user  = $user;
        $this->event = $event;
    }

    public function handle()
    {
        $chatId = '7748237713'; // 替換成目標 Chat ID

        try {
            // 發送 Telegram 推播通知
            Notification::route(TelegramChannel::class, $chatId)
                ->notify(new TelegramNotification($this->user, $this->event));

            // 執行成功後可以記錄日誌或進行其他操作
            echo "Telegram 推播通知已發送成功";
            // 模拟发送 Telegram 通知

        } catch (\Exception $e) {
            // 捕捉例外並記錄錯誤日誌
            echo "Telegram 推播通知發送失敗";

        }
    }

}
