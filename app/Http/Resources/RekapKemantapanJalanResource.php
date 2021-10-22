<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RekapKemantapanJalanResource extends JsonResource
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
            "luas_jalan" => $this->LUAS,
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
}
