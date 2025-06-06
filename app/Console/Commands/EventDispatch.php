<?php

namespace App\Console\Commands;

use App\Jobs\sendDiscord;
use App\Jobs\SendEmail;
use App\Jobs\SendTelegram;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EventDispatch extends Command
{
    /**
     *
     * @var string
     */
    protected $signature = 'event:dispatch {eventType}';

    /**
     *
     * @var string
     */
    protected $description = 'Dispatch event notifications based on event type.';

    public function handle()
    {
        $events = Event::with([
            'eventNotifyChannels.notifyChannel',
            'userSubscribeEvents.user',
        ])->where('trigger_time', '<', Carbon::now())->get();

        foreach ($events as $event) {
            $this->processEventNotifications($event);
        }

        $this->info('所有通知已處理完成');
    }

    protected function processEventNotifications($event)
    {
        foreach ($event->eventNotifyChannels as $channel) {
            $channelName = $channel->notifyChannel->name;

            foreach ($event->userSubscribeEvents as $subscription) {
                $user = $subscription->user;

                try {
                    $this->sendNotification($channelName, $user, $event);
                } catch (\Exception $e) {
                    Log::error("通知失敗: 事件 ID: {$event->id}, 用户 ID: {$user->id}, 錯誤訊息: " . $e->getMessage());
                }
            }
        }
    }

    private function sendNotification($channelName, $user, $event)
    {
        $channelName = trim(strtolower($channelName));

        if (! in_array($channelName, ['email', 'line', 'telegram'])) {
            Log::error("未知的通知方式: [$channelName]");
            return;
        }

        if (! $user) {
            Log::error('使用者對象為 null，無法發送通知');
            return;
        }

        if (! isset($user->id)) {
            Log::error('使用者對象不包含 ID，可能是 array 而非 Model', ['user' => $user]);
            return;
        }

        Log::info("正在發送 {$channelName} 通知，事件 ID: {$event->id}, 用戶 ID: {$user->id}");

        switch ($channelName) {
            case 'email':
                SendEmail::dispatch($user, $event);
                break;

            case 'line':
                sendDiscord::dispatch($user, $event);
                break;

            case 'telegram':
                SendTelegram::dispatch($user, $event);
                break;
        }
    }

}
