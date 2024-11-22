<?php

namespace App\Jobs;

use App\Mail\EventMail;
use App\Models\users;
use App\Models\UserSubscribeEvents;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
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
    public function handle($id): void
    {
        //
        $users = users::where('id', $id)->first();
        $data = json_decode($users, true);
        $email = $data['email'];

        $subscribe = UserSubscribeEvents::where('user_id', $id)->get();
        foreach ($subscribe as $subscription) {
            // 訪問每個項目中的 event_id
            $eventIds[] = $subscription->event_id;
        }

        Mail::to($email)->send(new EventMail($users, $eventIds));
        echo "發送成功";
    }
}
