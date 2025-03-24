<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscribeEvents extends Model
{

    protected $table = 'user_subscribe_events';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(users::class, 'user_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }
}
