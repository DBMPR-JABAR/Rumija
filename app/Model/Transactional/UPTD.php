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

    public function rumija_pemanfaatan()
    {
        return $this->hasMany('App\Transactional\RumijaPemanfaatan', 'uptd');
    }
    public function rumija_permohonan()
    {
        return $this->hasMany('App\Transactional\RumijaPermohonan', 'uptd_id');
    }
    public function rumija_laporan()
    {
        return $this->hasMany('App\Transactional\RumijaReport', 'uptd_id');
    }
}
