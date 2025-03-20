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
     *
     * @param $user
     * @param $event
     */
    public function __construct($user, $event)
    {
        $this->user  = $user;
        $this->event = $event;
    }

    public function via(object $notifiable): array
    {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        $message = "您好 {$this->user->name}，以下是事件內容：\n"
            . "事件名稱：{$this->event->name}\n"
            . "觸發时间：{$this->event->trigger_time}\n"
            . "事件描述：{$this->event->description}";

        return TelegramMessage::create()
            ->content($message);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'user_id'  => $this->user->id,
            'event_id' => $this->event->id,
        ];
    }
}
