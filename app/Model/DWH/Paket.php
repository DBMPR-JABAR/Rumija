<?php

namespace App\Model\DWH;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $connection = 'dwh';
    protected $table = 'TBL_DINAS_TRX_PAKET';
}
