<?php

namespace App\Model\DWH;

use Illuminate\Database\Eloquent\Model;

class ProgressMingguan extends Model
{
    protected $connection = 'dwh';
    protected $table = 'TBL_UPTD_TRX_PROGRESS_MINGGUAN';

    protected $appends = ['DEADLINE','STATUS_PROYEK'];

    protected $dates = [
        'TANGGAL','DEADLINE'
    ];

    public function pembangunan()
    {
        return $this->belongsTo('App\Model\DWH\Pembangunan', 'NAMA_PAKET', 'NAMA_PAKET');
    }

    public function getDeadlineAttribute(){
        return $this->TANGGAL->addDays($this->WAKTU_KONTRAK);
    }

    public function getStatusProyekAttribute(){
        if($this->DEVIASI == 0) return "FINISH";

        $condDeviasi = $this->DEVIASI > -5;          // True jika deviasi > -5
        $condDeadline = $this->DEADLINE >= now();    // True jika Waktu Masih Ada, False jika lewat deadline

        // Jika Waktu Masih Ada dan Deviasi > -5
        if($condDeviasi && $condDeadline){
            return "ON PROGRESS";
        }else{
            // Jika Waktu Masih Ada, Tapi Deviasi <= -5
            $status = ($condDeadline) ? 'CRITICAL CONTRACT' : 'OFF PROGRESS';
            return $status;
        }
    }
}
