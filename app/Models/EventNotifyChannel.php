<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventNotifyChannel extends Model
{

    protected $table = 'event_notify_channel';
    public $timestamps = false;

    public function notifyChannel()
    {
        return $this->belongsTo(NotifyChannel::class, 'notify_channel_id', 'id');
    }

}
