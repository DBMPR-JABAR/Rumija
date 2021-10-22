<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MapJembatanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $id = strtoupper("ID");
        $nama_jembatan = strtoupper("NAMA_JEMBATAN");
        $lat = strtoupper("LAT");
        $lng = strtoupper("LNG");
        $panjang = strtoupper("PANJANG");
        $lebar = strtoupper("LEBAR");
        $jumlah_bentang = strtoupper("JUMLAH_BENTANG");
        $lokasi = strtoupper("LOKASI");
        $ruas_jalan = strtoupper("RUAS_JALAN");
        $ket = strtoupper("KET");
        $sup = strtoupper("SUP");
        $uptd = strtoupper("UPTD");
        $foto = strtoupper("FOTO");

        return [
            $id => $this->id,
            $nama_jembatan => $this->nama_jembatan,
            $lat => $this->lat,
            $lng => $this->lng,
            $panjang => $this->panjang,
            $lebar => $this->lebar,
            $jumlah_bentang => $this->jumlah_bentang,
            $lokasi => $this->lokasi,
            $ruas_jalan => $this->ruas_jalan,
            $ket => $this->ket,
            $sup => $this->sup,
            $uptd => $this->uptd,
            $foto => $this->foto()->pluck('foto')
        ];


    }
}
