<?php 

namespace App\Http\Repository;

use Carbon\Carbon;
use App\Models\EventNotifyChannel;
use Illuminate\Support\Facades\DB;
use App\Models\Event;

class EventRepository
{
    public function create(array $input){
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
    public function update($id, array $input) {
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
    
}