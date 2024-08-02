<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function eventNotifyChannels()
    {
        return $this->hasMany(EventNotifyChannel::class, 'id', 'id');
    }
}
