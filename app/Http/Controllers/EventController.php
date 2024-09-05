<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventNotifyChannel;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use App\Http\Transformer\GetEventsTransformer;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Support\Facades\DB;

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
    public function create(CreateEventRequest $request)
{
    DB::beginTransaction();

    try {
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
        DB::commit();

        return response()->json($event);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'An error occurred while creating the event.'], 500);
    }
}

public function update($id, UpdateEventRequest $request)
{
    DB::beginTransaction();

    try {
        $updateEvent = Event::where('id', $id)->firstOrFail();

        if ($request->has('name')) {
            $updateEvent->name = $request->name;
        }

        if ($request->has('trigger_time')) {
            $updateEvent->trigger_time = Carbon::parse($request->trigger_time);
        }

        $updateEvent->save();

        if ($request->has('event_notify_channels')) {
            $updateEvent->eventNotifyChannels()->delete();

            $eventNotifyChannels = [];
            foreach ($request->event_notify_channels as $eventNotifyChannelId) {
                $eventNotifyChannel = new EventNotifyChannel();
                $eventNotifyChannel->notify_channel_id = $eventNotifyChannelId;
                $eventNotifyChannel->message = 'test';
                $eventNotifyChannels[] = $eventNotifyChannel;
            }

            $updateEvent->eventNotifyChannels()->saveMany($eventNotifyChannels);
        }

        DB::commit();

        return response()->json($updateEvent);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json(['error' => 'An error occurred while updating the event.'], 500);
    }
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
    }        
    public function delete($id, Request $request)
    {
        $deleteEvent = Event::where('id', $id)->first();
        $deleteEvent-> delete();

        return response()->json($deleteEvent);
    }
}