<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IncentiveFactor extends JsonResource{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request){
        //return parent::toArray($request);
        return [
            'id' =>$this->id,
            'kpi_type_id' =>$this->kpi_type_id,
            'name' =>$this->name,
            'from' =>$this->from,
            'to' =>$this->to,
            'multiplication_factor' =>$this->multiplication_factor,
      ];
    }
}
