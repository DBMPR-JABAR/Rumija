<?php

namespace App\Model\DWH;

use Illuminate\Database\Eloquent\Model;

class Jembatan extends Model
{
    protected $connection = 'dwh';
    protected $table = 'TBL_UPTD_TRX_JEMBATAN';
}
