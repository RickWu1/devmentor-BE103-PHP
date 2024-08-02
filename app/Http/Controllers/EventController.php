<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventNotifyChannel;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventController extends Controller{

    public function hello()
    {
        return response()->json(['message' => 'Hello World from controller!']);
    }
    public function index()
    {
        $events = Event::all(); 

        $response = [];
        foreach ($events as $event) {
            $eventNotifyChannelIds = [];
            foreach ($event->eventNotifyChannels as $eventNotifyChannel) {
                $eventNotifyChannelIds[] = $eventNotifyChannel->notify_channels_id;
            }

            $response[] = [
                'id' => $event->id,
                'name' => $event->name,
                'trigger_time' => $event->trigger_time,
                'event_notify_channels' => $eventNotifyChannelIds,
            ];
        }

        return response()->json($response);
    }
    public function create(Request $request)
    {
        $event = new Event();
        $event->name = $request->name;
        $event->trigger_time = $request->trigger_time;
        $event->save();

        return response()->json($event);
    }

    public function update($id, Request $request)
    {
        try {
            $event = Event::findOrFail($id);
        } catch (ModelNotFoundException $th) {
            return response()->json(['message' => 'event not found'], 404);
        }
        $event->name = $request->name;
        $event->trigger_time = $request->trigger_time;
        $event->save();

        return response()->json($event);
    }
    public function show($id, Request $request)
    {
        try {
            $events = Event::findOrFail($id);
        } catch (ModelNotFoundException $th) {
            return response()->json(['message' => 'event not found'], 404);
        }
        return response()->json($events);
    }
    public function delete($id, Request $request)
    {
        try {
            $event = Event::findOrFail($id);
        } catch (ModelNotFoundException $th) {
            return response()->json(['message' => 'event not found'], 404);
        }
        $event->delete();
    }
}