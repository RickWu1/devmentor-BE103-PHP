<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $event;

    /**
     * 构造函数.
     *
     * @param $user 用户对象
     * @param $event 事件对象
     */
    public function __construct($user, $event)
    {
        $this->user  = $user;
        $this->event = $event;
    }

    /**
     * 指定通知的发送通道.
     */
    public function via(object $notifiable): array
    {
        return [TelegramChannel::class];
    }

    /**
     * 构建 Telegram 消息.
     */
    public function toTelegram($notifiable)
    {
        $message = "您好 {$this->user->name}，以下是事件详情：\n"
            . "事件名称：{$this->event->name}\n"
            . "触发时间：{$this->event->trigger_time}\n"
            . "描述：{$this->event->description}";

        return TelegramMessage::create()
            ->content($message);
    }

    /**
     * 将通知转为数组表示 (可选).
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id'  => $this->user->id,
            'event_id' => $this->event->id,
        ];
    }
}
