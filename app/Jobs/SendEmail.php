<?php
namespace App\Jobs;

use App\Mail\EventMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $event;

    public function __construct($user, $event)
    {
        $this->user  = $user;
        $this->event = $event;

    }

    public function handle(): void
    {
        Log::info("開始發送郵件，User ID: " . $this->user);

        // users::find($this->userId);

        // if (! $this->user) {
        //     Log::error("找不到使用者 ID: " . $this->user);
        //     return;
        // }

        $email = $this->user->email;

        // $subscribe = UserSubscribeEvents::where('user_id', $this->event->id)->get();
        // $eventIds  = [];

        // foreach ($subscribe as $subscription) {
        //     $eventIds[] = $subscription->event_id;
        // }

        // if (empty($eventIds)) {
        //     Log::warning("使用者 ID: " . $this->id . " 沒有訂閱任何事件");
        //     return;
        // }

        try {
            Mail::to($email)->send(new EventMail($this->user, [$this->event->id]));
            Log::info("郵件已成功發送給: " . $email);
        } catch (\Exception $e) {
            Log::error("郵件發送失敗: " . $e->getMessage());
        }
    }
}
