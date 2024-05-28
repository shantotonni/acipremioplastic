<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseLine extends JsonResource{
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
            'service_income_base_line' =>$this->service_income_base_line,
            'sp_tractor_base_line' =>$this->sp_tractor_base_line,
            'sp_nmpt_base_line' =>$this->sp_nmpt_base_line,
            'sp_tractor_plus_nmpt_base_line' =>$this->sp_tractor_plus_nmpt_base_line,
      ];
    }
}
