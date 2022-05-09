<?php

namespace App\Transactional;

use Illuminate\Database\Eloquent\Model;

class RumijaPemanfaatan extends Model
{
    //
    protected $table = 'rumija';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
    
    public function ruas()
    {
        return $this->belongsTo('App\Model\Transactional\RuasJalan', 'ruas_jalan_id','id_ruas_jalan');
    }

    public function uptd()
    {
        return $this->belongsTo('App\Model\Transactional\UPTD', 'uptd');
    }
}
