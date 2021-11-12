<?php

namespace App\Model\Transactional;

use Illuminate\Database\Eloquent\Model;
use app\Transactional\RumijaInventarisasi;

class RuasJalan extends Model
{
    protected $table = "master_ruas_jalan";

    public function uptd()
    {
        return $this->belongsTo('App\Model\Transactional\UPTD', 'uptd_id');
    }

    public function users()
    {
        // return $this->belongsToMany('App\User','user_id');
        return $this->belongsToMany('App\User','user_master_ruas_jalan','user_id','master_ruas_jalan_id');

    }

    public function inventarisRumija()
    {
        return $this->hasMany(RumijaInventarisasi::class, 'id_ruas_jalan', 'id_ruas_jalan');
    }
}
