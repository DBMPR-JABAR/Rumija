<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class NotificationResource extends JsonResource
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
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'datePublish' => Carbon::parse($this->created_at)->format('d/m/Y')
        ];
    }
    public function with($request)
    {
        return [
            'status' => 'success'
        ];
    }
}
