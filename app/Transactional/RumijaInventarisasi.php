<?php

namespace App\Transactional;

use Illuminate\Database\Eloquent\Model;

class RumijaInventarisasi extends Model
{
    //
    protected $table = "rumija_inventarisasi";
    protected $guarded = [];
    public function list_details()
    {
        return $this->hasMany('App\Transactional\RumijaInventarisasiDetail', 'rumija_inventarisasi_id');
    }
    public function detail()
    {
        return $this->hasOne('App\Transactional\RumijaInventarisasiDetail', 'rumija_inventarisasi_id');
    }
    public function kategori_inventarisasi()
    {
        return $this->belongsTo('App\Transactional\RumijaInventarisasiKategori', 'rumija_inventarisasi_kategori_id');
    }
}
