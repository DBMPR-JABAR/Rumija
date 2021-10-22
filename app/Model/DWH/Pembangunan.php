<?php

namespace App\Model\DWH;

use Illuminate\Database\Eloquent\Model;

class Pembangunan extends Model
{
    protected $connection = 'dwh';
    protected $table = 'TBL_UPTD_TRX_PEMBANGUNAN';
    protected $primaryKey = 'KODE_PAKET';

    public function progressMingguan()
    {
        return $this->hasMany('App\Model\DWH\ProgressMingguan', 'NAMA_PAKET', 'NAMA_PAKET');
    }
}
