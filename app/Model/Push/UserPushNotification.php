<?php

namespace App\Model\Push;

use Illuminate\Database\Eloquent\Model;

class UserPushNotification extends Model
{
    protected $table = "user_push_notification";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
