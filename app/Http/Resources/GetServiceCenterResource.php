<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GetServiceCenterResource extends JsonResource
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
            'id' =>$this->id,
            'responsible_person' =>$this->responsible_person,
            'address' =>$this->address,
            'mobile' =>$this->mobile,
            'lat' =>$this->lat,
            'lon' =>$this->lon,
            'area' =>$this->area->name,
        ];
    }
}
