<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            "nama_paket" => $this->NAMA_PAKET,
            "date_from" => Carbon::parse($this->DATE_FROM)->format('d-m-Y'),
            "date_to" => Carbon::parse($this->DATE_TO)->format('d-m-Y'),
            "rencana" => (float) $this->RENCANA,
            "realisasi" => (float) $this->REALISASI,
            "deviasi" => (float) $this->DEVIASI
        ];
    }

    public function with($request)
    {
        return [
            'status' => 'success'
        ];
    }
}
