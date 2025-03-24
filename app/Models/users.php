<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class users extends Model
{

    protected $table = 'users';
    public $timestamps = false;
    public function UserSubscribe()
    {
        return $this->hasMany(UserSubscribeEvents::class, 'user_id', 'id');
    }

}
