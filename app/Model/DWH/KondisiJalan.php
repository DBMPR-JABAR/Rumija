<?php

namespace App\Model\DWH;

use Illuminate\Database\Eloquent\Model;

class KondisiJalan extends Model
{
    protected $connection = 'dwh';
    protected $table = 'TBL_UPTD_TRX_MASTER_KONDISI_JALAN';

    protected $fillable = [
        'ruas_jalan', 'nama_kota', 'km_asal',
    ];
}
