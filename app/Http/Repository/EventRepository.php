<?php

namespace App\Http\Repository;

use App\Models\Event;
use App\Models\EventNotifyChannel;
use App\Models\User;
use App\Models\UserSubscribeEvents;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventRepository
{
    public function create(array $input)
    {
        DB::beginTransaction();

        try {
            $event = new Event();
            $event->name = $input['name'];
            $event->trigger_time = Carbon::parse($input['trigger_time']);
            $event->save();

            $eventNotifyChannels = [];
            foreach ($input['event_notify_channels'] as $eventNotifyChannelId) {
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

            return response()->json(['error' => 'An error occurred while updating the event.'], 500);
        }
    }

    public function update($id, array $input)
    {
        DB::beginTransaction();

        try {
            $updateEvent = Event::where('id', $id)->firstOrFail();

            if (isset($input['name'])) {
                $updateEvent->name = $input['name'];
            }

            if (isset($input['trigger_time'])) {
                $updateEvent->trigger_time = Carbon::parse($input['trigger_time']);
            }

            $updateEvent->save();

            if (isset($input['event_notify_channels'])) {

                $updateEvent->eventNotifyChannels()->delete();

                $eventNotifyChannels = [];
                foreach ($input['event_notify_channels'] as $eventNotifyChannelId) {
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

    public function delete($id)
    {
        $deleteEvent = Event::where('id', $id)->first();
        $deleteEvent->delete();

        return response()->json($deleteEvent);
    }

    public function creatUser(array $input)
    {
        $userEvent = new User();
        $userEvent->name = $input['name'];
        $userEvent->email = $input['email'];
        $userEvent->password = $input['password'];
        $userEvent->save();

        return response()->json($userEvent);
    }

    public function deleteUser($id)
    {
        $deleteUser = User::where('id', $id)->first();
        $deleteUser->delete();

        return response()->json($deleteUser);

    }

    public function subscribe($id, array $input)
    {
        $subscribeEvent = Event::where('id', $id)->firstOrFail();
        $userSubscribeEvents = [];
        foreach ($input['user_id'] as $userSubscribeEventsId) {
            $userSubscribeEvent = new UserSubscribeEvents();
            $userSubscribeEvent->event_id = $id;
            $userSubscribeEvent->user_id = $userSubscribeEventsId;
            $userSubscribeEvents[] = $userSubscribeEvent;
        }

        $subscribeEvent->UserSubscribeEvents()->saveMany($userSubscribeEvents);
        return response()->json($userSubscribeEvents);
    }
}
