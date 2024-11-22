<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function eventNotifyChannels()
    {
        return $this->hasMany(EventNotifyChannel::class, 'event_id', 'id');
    }

    public function userSubscribeEvents()
    {
        return $this->hasMany(UserSubscribeEvents::class, 'event_id');
    }
}
