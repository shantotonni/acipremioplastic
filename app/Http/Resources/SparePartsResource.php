<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SparePartsResource extends JsonResource
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
            'ProductName' =>$this->ProductName,
            'UnitPrice' =>$this->UnitPrice,
            'MRP' =>$this->MRP,
            'Business' =>$this->Business,
            'total_quantity' =>$this->stock->sum('BatchQTY'),
        ];
    }
}
