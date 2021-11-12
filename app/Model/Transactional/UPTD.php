<?php

namespace App\Model\Transactional;

use Illuminate\Database\Eloquent\Model;
use app\Transactional\RumijaInventarisasi;


class UPTD extends Model
{
    protected $table = "landing_uptd";

    public function library_sup()
    {
        return $this->hasMany('App\Model\Transactional\SUP', 'uptd_id');
    }
    public function library_pemeliharaan()
    {
        return $this->hasMany('App\Model\Transactional\PekerjaanPemeliharaan', 'uptd_id')->where('is_deleted', '!=', 1);
    }


    public function inventarisRumija()
    {
        return $this->hasMany(RumijaInventarisasi::class, 'uptd_id');
    }
}
