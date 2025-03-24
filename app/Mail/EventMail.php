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
    public $eventId;

    public function __construct($user, $eventId)
    {
        $this->user = $user;
        $this->eventId = $eventId;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '事件通知'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'eventMail',
            with: [
                'user' => $this->user,
                'eventId' => $this->eventId,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
