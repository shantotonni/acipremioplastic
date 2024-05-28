<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SparePartsDetailsResource extends JsonResource
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
            'ProductCode' =>$this->ProductCode,
            'ReceiveQTY' =>$this->ReceiveQTY,
            'BatchQTY' =>$this->BatchQTY,
            'DepotName' =>$this->depot->DepotName,
            'Business' =>$this->Business,
        ];
    }
}
