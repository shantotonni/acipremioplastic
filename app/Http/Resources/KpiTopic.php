<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KpiTopic extends JsonResource{
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
            'name' =>$this->name,
            'kpi_type_id' =>$this->kpi_type_id,
            'kpi_group_id' =>$this->kpi_group_id,
            'sl' =>$this->sl,
            'mark' =>$this->mark,
            'ach_from' =>$this->ach_from,
            'ach_to' =>$this->ach_to,
            'weight' =>$this->weight,
            'multiplication_factor' =>$this->multiplication_factor,
      ];
    }
}
