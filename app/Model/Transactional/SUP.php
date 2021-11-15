<?php

namespace App\Model\Transactional;

use App\Model\Transactional\RuasJalan;
use Illuminate\Database\Eloquent\Model;
use app\Transactional\RumijaInventarisasi;

class SUP extends Model
{
    protected $table = "utils_sup";
    public function library_user()
    {
        return $this->hasMany('App\User', 'sup_id')->where('blokir', '!=', 'Y');
    }
    public function library_pemeliharaan()
    {
        return $this->hasMany('App\Model\Transactional\PekerjaanPemeliharaan', 'sup_id')->where('is_deleted', '!=', 1);
    }

    public function ruasJalan()
    {
        return $this->hasMany('App\Model\Transactional\RuasJalan', 'kd_sppjj', 'kd_sup');
    }

    public function inventarisRumija()
    {
        return $this->hasMany(RumijaInventarisasi::class, 'kd_sup', 'kd_sup');
    }
}
