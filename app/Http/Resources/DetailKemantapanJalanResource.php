<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailKemantapanJalanResource extends JsonResource
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
            "nomor_ruas" => $this->NO_RUAS,
            "sub_ruas" => $this->SUB_RUAS,
            "suffix" => $this->SUFFIX,
            "bulan" => $this->BULAN,
            "year" => $this->TAHUN,
            "kota" => $this->KOTA_KAB,
            "lat_awal" => $this->LAT_AWAL,
            "lng_awal" => $this->LONG_AWAL,
            "lat_akhir" => $this->LAT_AKHIR,
            "lng_akhir" => $this->LONG_AKHIR,
            "keterangan" => $this->KETERANGAN,
            "spp" => $this->SUP,
            "luas_jalan" => $this->LUAS,
            "uptd" => $this->UPTD,
            "kondisi_jalan" => [
                [
                   "domain"=> "Hancur",
                    "measure"=> $this->HANCUR
                ],
                [
                    "domain"=> "Sangat Parah",
                    "measure"=> $this->SANGAT_PARAH
                ],
                [
                    "domain"=> "Parah",
                    "measure"=> $this->PARAH
                ],
                [
                    "domain"=> "Jelek",
                    "measure"=> $this->JELEK
                ],
                [
                    "domain"=> "Sedang",
                    "measure"=> $this->SEDANG
                ],
                [
                    "domain"=> "Baik",
                    "measure"=> $this->BAIK
                ],
                [
                    "domain"=> "Sangat Baik",
                    "measure"=> $this->SANGAT_BAIK
                ]
            ]
        ];
    }
    public function with($request)
    {
        return [
            'status' => 'success'
        ];
    }
}
