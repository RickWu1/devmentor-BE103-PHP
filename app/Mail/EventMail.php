<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $user;
    public $eventIds;

    /**
     * 建立函式，接收使用者和事件 ID 陣列
     */
    public function __construct($user, array $eventIds)
    {
        $this->user = $user;
        $this->eventIds = $eventIds;
    }

    /**
     * 設定郵件標題
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '事件通知'
        );
    }

    /**
     * 設定郵件內容
     */
    public function content(): Content
    {
        return new Content(
            view: 'eventMail',
            with: [
                'user' => $this->user,
                'eventIds' => $this->eventIds,
            ]
        );
    }

    /**
     * 附件（如果有）
     */
    public function attachments(): array
    {
        return [];
    }
}
