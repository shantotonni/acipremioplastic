<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserKpi extends JsonResource{
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
            'date' =>$this->date,
            'user_id' =>$this->user_id,
            'kpi_type_id' =>$this->kpi_type_id,
            'total_kpi_target' =>$this->total_kpi_target,
            'total_kpi_ach' =>$this->total_kpi_ach,
      ];
    }
}
