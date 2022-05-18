<?php

namespace App\Transactional;

use Illuminate\Database\Eloquent\Model;

class RumijaTipe extends Model
{
    //
    protected $table = 'rumija_tipe';
    protected $guarded = [];

    public function library_rumija_report()
    {
        return $this->hasMany('App\Transactional\RumijaReport', 'rumija_tipe_id');
    }
    public function library_rumija_permohonan()
    {
        return $this->hasMany('App\Transactional\RumijaPermohonan', 'tipe_permohonan','kode');
    }
}
