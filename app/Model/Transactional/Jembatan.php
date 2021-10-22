<?php

namespace App\Model\Transactional;

use Illuminate\Database\Eloquent\Model;

class Jembatan extends Model
{
    protected $table = "master_jembatan";

    public $timestamps = true;

    public function foto()
    {
        return $this->hasMany('App\Model\Transactional\JembatanFoto', 'id_jembatan');
    }

    public function ruasJalan()
    {
        return $this->belongsTo('App\Model\Transactional\RuasJalan', 'ruas_jalan_id');
    }

    public function uptd()
    {
        return $this->belongsTo('App\Model\Transactional\UPTD', 'uptd_id');
    }
}
