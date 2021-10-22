<?php

namespace App\Model\DWH;

use Illuminate\Database\Eloquent\Model;

class RawanBencana extends Model
{
    protected $connection = 'dwh';
    protected $table = 'MASTER_RAWAN_BENCANA'; 
}
