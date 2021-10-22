<?php

namespace App\Model\Transactional;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = "user_pegawai";

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function bidang()
    {
        return $this->belongsTo('App\Model\Transactional\Bidang', 'bidang_id');
    }
}
