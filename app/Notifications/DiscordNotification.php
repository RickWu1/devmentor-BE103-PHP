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

    public function via($notifiable)
    {
        return [DiscordChannel::class];
    }

    public function toDiscord($notifiable)
    {
        $message = "您好 {$this->user->name}，以下是事件详情：\n"
            . "事件名称：{$this->event->name}\n"
            . "触发时间：{$this->event->trigger_time}\n"
            . "描述：{$this->event->description}";

        return [
            'content' => $message, // 從資料庫取得訊息
        ];
    }
}
