<?php

namespace App\Model\Transactional;

use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    protected $table = "user_bidang";

    public function pegawai()
    {
        return $this->hasMany('App\Model\Transactional\Pegawai', 'bidang_id');
    }
}
