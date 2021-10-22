<?php

namespace App\Model\Transactional;
use Illuminate\Database\Eloquent\Model;

class DisposisiPenanggungJawab extends Model
{
    protected $table = "disposisi_penanggung_jawab"; 

    
    public function keterangan_role() {
        return $this->belongsTo('App\Model\Transactional\Role', 'user_role_id');
    }
}

