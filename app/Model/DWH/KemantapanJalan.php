<?php

namespace App\Model\DWH;

use Illuminate\Database\Eloquent\Model;

class KemantapanJalan extends Model
{
    protected $connection = 'dwh';
    protected $table = 'TBL_XLS_TRX_KEMANTAPAN_JALAN';
    protected $primaryKey = 'ID_KEMANTAPAN';
}
