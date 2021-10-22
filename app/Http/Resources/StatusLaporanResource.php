<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatusLaporanResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [];
        if($this->status != "Done") $data["id"] = $this->id;
        $data["nomorPengaduan"] = $this->nomorPengaduan;
        if($this->status == "Progress") $data['deskripsi'] = $this->deskripsi;

        return $data;
    }
    public function with($request)
    {
        return [
            'status' => 'success'
        ];
    }
}
