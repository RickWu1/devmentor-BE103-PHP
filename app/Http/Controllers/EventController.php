<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventNotifyChannel;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use App\Http\Transformer\GetEventsTransformer;

class EventController extends Controller{

    public function hello()
    {
        return response()->json(['message' => 'Hello World from controller!']);
    }
    public function index(GetEventsTransformer $transformer)
    {
        $events = Event::with('eventNotifyChannels')->get();
        $response = $transformer->transform($events);
        return response()->json($response);
    }
    public function create(Request $request)
    {
        $event = new Event();
        $event->name = $request->name;
        $event->trigger_time = Carbon::parse($request->trigger_time);
        $event->save();

        $eventNotifyChannels = [];
        foreach ($request->event_notify_channels as $eventNotifyChannelId) {
            $eventNotifyChannel = new EventNotifyChannel();
            $eventNotifyChannel->notify_channel_id = $eventNotifyChannelId;
            $eventNotifyChannel->message = 'test';
            $eventNotifyChannels[] = $eventNotifyChannel;
        }

        $event->eventNotifyChannels()->saveMany($eventNotifyChannels);


        return response()->json($event);
    }

    public function update($id, Request $request)
    {
        $updateEvent = Event::where('id', $id)->first();
        $updateEvent->name = $request->name;
        $updateEvent->trigger_time = Carbon::parse($request->trigger_time);
        $updateEvent->save();

        $updateEvent->eventNotifyChannels()->delete();

        $eventNotifyChannels = [];
        foreach ($request->event_notify_channels as $eventNotifyChannelId) {
            $eventNotifyChannel = new EventNotifyChannel();
            $eventNotifyChannel->notify_channel_id = $eventNotifyChannelId;
            $eventNotifyChannel->message = 'test';
            $eventNotifyChannels[] = $eventNotifyChannel;
        }

        $updateEvent->eventNotifyChannels()->saveMany($eventNotifyChannels);

        return response()->json($updateEvent);
    }
    public function get($event_id)
    {
        $event = Event::find($event_id);
        $response = [
            'id' => $event->id,
            'name' => $event->name,
            'trigger_time' => $event->trigger_time,
            'event_notify_channels' => $event->eventNotifyChannels->pluck('notify_channel_id'),
        ];
        return response()->json($response);
    }        public function delete($id, Request $request)
    {
        $deleteEvent = Event::where('id', $id)->first();
        $deleteEvent-> delete();

        return response()->json($deleteEvent);
    }
}