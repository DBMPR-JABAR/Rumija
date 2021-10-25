<?php

namespace App\Transactional;

use Illuminate\Database\Eloquent\Model;

class RumijaInventarisasiKategori extends Model
{
    //
    protected $table = "rumija_inventarisasi_kategori";
    protected $guarded = [];
    public function list_inventaris()
    {
        return $this->hasMany('App\Transactional\RumijaInventarisasi', 'rumija_inventarisasi_kategori_id');
    }
}
