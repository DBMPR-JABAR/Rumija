<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KerusakanJalanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "nomorPengaduan" => $this->nomorPengaduan,
            "nama" => $this->nama,
            "nik" => $this->nik,
            "alamat" => $this->alamat,
            "telp" => $this->telp,
            "email" => $this->email,
            "jenis" => $this->jenis,
            "gambar" => $this->gambar,
            "lokasi" => $this->lokasi,
            "lat" => "$this->lat",
            "long" => "$this->long",
            "deskripsi" => $this->deskripsi,
            "status" => $this->status,
            "uptd_id" => $this->uptd_id,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
    public function with($request)
    {
        return [
            'status' => 'success'
        ];
    }
}
