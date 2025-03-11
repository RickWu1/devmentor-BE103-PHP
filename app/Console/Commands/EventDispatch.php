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
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:dispatch {eventType}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch event notifications based on event type.';

    /**
     * Execute the console command.
     */
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
                    Log::error("通知失败: 事件 ID: {$event->id}, 用户 ID: {$user->id}, 錯誤信息: " . $e->getMessage());
                }
            }
        }
    }

    private function sendNotification($channelName, $user, $event)
    {
        $channelName = trim(strtolower($channelName)); // 確保匹配
        Log::info("通知方式: [$channelName]");     // 記錄 Log 來確認

        if (! $user) {
            Log::error("使用者對象為 null，無法發送通知");
            return;
        }

        if (! isset($user->id)) {
            Log::error("使用者對象不包含 ID，可能是 array 而非 Model", ['user' => $user]);
            return;
        }

        switch ($channelName) {
            case 'email':
                Log::info("正在發送 Email 通知，事件 ID: {$event->id}, 用戶 ID: {$user->id}");
                SendEmail::dispatch($user, $event);
                break;

            case 'line':
                Log::info("正在發送 Discord 通知，事件 ID: {$event->id}, 用戶 ID: {$user->id}");
                sendDiscord::dispatch($user, $event);
                break;

            case 'telegram':
                Log::info("正在發送 Telegram 通知，事件 ID: {$event->id}, 用戶 ID: {$user->id}");
                SendTelegram::dispatch($user, $event);
                break;

            default:
                Log::error("未知的通知方式: [$channelName]");
                break;
        }
    }

}
