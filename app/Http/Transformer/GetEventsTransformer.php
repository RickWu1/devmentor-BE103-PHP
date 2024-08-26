<?php

namespace App\Http\Transformer;

use App\Models\Event;
use Illuminate\Support\Collection;

class GetEventsTransformer
{
    /**
     * @param  Collection  $events
     *
     * @return array
     */
    public function transform(Collection $events): array
    {
        $events->transform(function ($event) {
            return [
                'id' => $event->id,
                'name' => $event->name,
                'trigger_time' => $event->trigger_time,
                'event_notify_channels' => $event->eventNotifyChannels->pluck('notify_channel_id'),
            ];
        });

        return $events->all();
    }
}