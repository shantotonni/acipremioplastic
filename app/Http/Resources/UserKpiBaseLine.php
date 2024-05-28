<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserKpiBaseLine extends JsonResource{
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
            'user_kpi_id' =>$this->user_kpi_id,
            'kpi_group_id' =>$this->kpi_group_id,
            'amount' =>$this->amount,
      ];
    }
}
