<?php

namespace App\Model\DWH;

use Illuminate\Database\Eloquent\Model;

class VehicleCounting extends Model
{
    protected $connection = 'dwh';
    protected $table = 'TBL_VIANA_TRX_VEHICLE_COUNTING';
    protected $guarded = [];
}
