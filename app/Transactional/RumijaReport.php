<?php

namespace App\Transactional;

use Illuminate\Database\Eloquent\Model;

class RumijaReport extends Model
{
    //
    protected $table = 'rumija_report';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
    public function data_sup()
    {
        return $this->belongsTo('App\Model\Transactional\SUP', 'sup_id');
    }
    public function ruas()
    {
        return $this->belongsTo('App\Model\Transactional\RuasJalan', 'ruas_jalan_id','id_ruas_jalan');
    }
}
