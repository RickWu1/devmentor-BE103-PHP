<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        $channels = [];

        if ($notifiable->prefersMail()) {
            $channels[] = 'mail';
        }

        if ($notifiable->prefersTelegram()) {
            $channels[] = 'Telegram';
        }

        if ($notifiable->prefersDiscord()) {
            $channels[] = 'Discord';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function testEmail($id)
    {
        $job = new SendEmail();
        $response = $job->handle($id);
        echo $response;
    }

    public function sendTelegramNotification()
    {
        $job = new SendTelegram();
        $response = $job->handle();
        echo $response;
    }

    public function sendDiscordNotification()
    {
        $job = new sendDiscord();
        $response = $job->handle();
        echo $response;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
