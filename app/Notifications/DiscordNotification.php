<?php

namespace App\Notifications;

use App\Channels\DiscordChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

// 引入 DiscordChannel

class DiscordNotification extends Notification
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
        $this->user = $user;
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return [DiscordChannel::class];
    }

    public function toDiscord($notifiable)
    {
        $message = "您好 {$this->user->name}，以下是事件內容：\n"
            . "事件名稱：{$this->event->name}\n"
            . "觸發时间：{$this->event->trigger_time}\n"
            . "事件描述：{$this->event->description}";

        return [
            'content' => $message,
        ];
    }
}
