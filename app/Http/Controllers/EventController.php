<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Service\EventService;
use App\Http\Transformer\GetEventsTransformer;
use App\Jobs\sendDiscord;
use App\Jobs\SendEmail;
use App\Jobs\SendTelegram;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{

    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index(GetEventsTransformer $transformer)
    {
        $events   = Event::with('eventNotifyChannels')->get();
        $response = $transformer->transform($events);
        return response()->json($response);
    }

    public function create(CreateEventRequest $request)
    {
        $event = $this->eventService->create($request->all());
        return response()->json($event);
    }

    public function update($id, UpdateEventRequest $request)
    {
        $event = $this->eventService->update($id, $request->all());
        return response()->json($event);
    }

    public function get($event_id)
    {
        $event = $this->eventService->get($event_id);
        return response()->json($event);
    }

    public function delete($id, Request $request)
    {
        $event = $this->eventService->delete($id, $request);
        return response()->json($event);
    }

    public function creatUser(Request $request)
    {
        $event = $this->eventService->creatUser($request->all());
        return response()->json($event);

    }

    public function deleteUser($id, Request $request)
    {
        $event = $this->eventService->deleteUser($id, $request);
        return response()->json($event);
    }

    public function subscribe($id, Request $request)
    {
        $event = $this->eventService->subscribe($id, $request->all());
        return response()->json($event);
    }

    public function testEmail($id)
    {
        $job      = new SendEmail();
        $response = $job->handle($id);
        echo $response;
    }

    public function sendTelegramNotification($user, $event)
    {
        $job      = new SendTelegram($user, $event);
        $response = $job->handle();
        Log::info("Telegram 通知发送成功：{$response}");
        echo $response;
    }

    public function sendDiscordNotification($user, $event): void
    {
        $job      = new sendDiscord($user, $event);
        $response = $job->handle();
        echo $response;
    }

    // public function testqueue(): void
    // {
    //     dispatch(new TestJob());

    // }

    public function testEventDispatch()
    {
        $events = Event::with([
            'eventNotifyChannels.notifyChannel',
            'userSubscribeEvents.user',
        ])->where('trigger_time', '<', Carbon::now())->get();

        foreach ($events as $event) {
            $this->processEventNotifications($event);
        }
        Log::info('所有通知已处理完成');
        return response()->json(['message' => '所有通知已处理完成'], 200);

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
                    Log::error("通知失败: 事件 ID: {$event->id}, 用户 ID: {$user->id}");
                }

            }
        }
    }
    private function sendNotification($channelName, $user, $event)
    {
        switch ($channelName) {
            case 'email':
                $this->testEmail($user->id);
                break;

            case 'line':
                $this->sendDiscordNotification($user, $event);
                break;

            case 'telegram':
                $this->sendTelegramNotification($user, $event);
                break;

            default:
                $this->error("未知的通知方式: $channelName");
                break;
        }
    }

}
