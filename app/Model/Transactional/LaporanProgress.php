<?php

namespace App\Model\Transactional;

use Illuminate\Database\Eloquent\Model;

class LaporanProgress extends Model
{
    protected $table = 'monitoring_laporan_petugas';
    protected $guarded = [];

    public function pegawai()
    {
        return $this->belongsTo('App\Model\Transactional\Pegawai', 'pegawai_id');
    }

    public function laporan()
    {
        return $this->belongsTo('App\Model\Transactional\LaporanMasyarakat', 'laporan_id');
    }
}
