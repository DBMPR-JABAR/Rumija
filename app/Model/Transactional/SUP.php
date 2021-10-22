<?php

namespace App\Model\Transactional;

use Illuminate\Database\Eloquent\Model;

class SUP extends Model
{
    protected $table = "utils_sup";
    public function library_user()
    {
        return $this->hasMany('App\User', 'sup_id')->where('blokir', '!=', 'Y');
    }
    public function library_pemeliharaan()
    {
        return $this->hasMany('App\Model\Transactional\PekerjaanPemeliharaan', 'sup_id')->where('is_deleted', '!=', 1);
    }
}
