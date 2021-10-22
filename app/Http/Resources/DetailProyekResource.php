<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class DetailProyekResource extends JsonResource
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
            "date" => Carbon::parse($this->TANGGAL)->format("d-m-2Y"),
            "jenis_pekerjaan" => $this->JENIS_PEKERJAAN,
            "ruas_jalan" => $this->RUAS_JALAN,
            "status" => $this->STATUS_PROYEK
        ];
    }
}
