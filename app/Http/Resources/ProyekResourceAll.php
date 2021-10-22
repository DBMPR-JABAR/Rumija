<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProyekResource extends JsonResource
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
            'KODE_PAKET' => $this->KODE_PAKET,
            'NOMOR_KONTRAK' => $this->NOMOR_KONTRAK,
            'NAMA_PAKET' => $this->NAMA_PAKET,
            'TGL_KONTRAK' => $this->TGL_KONTRAK,
            'WAKTU_PELAKSANAAN_BLN' => $this->WAKTU_PELAKSANAAN_BLN,
            'WAKTU_PELAKSANAAN_HK' => $this->WAKTU_PELAKSANAAN_HK,
            'TOTAL_SISA_LELANG' => $this->TOTAL_SISA_LELANG,
            'UPTD' => $this->UPTD,
            'PROGRESS_MINGGUAN' => GeneralResource::collection($this->progressMingguan)
        ];
    }

    public function with($request)
    {
        return [
            'status' => 'success'
        ];
    }
}
