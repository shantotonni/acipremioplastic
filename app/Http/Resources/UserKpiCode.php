<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserKpiCode extends JsonResource{
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
            'user_id' =>$this->user_id,
            'service_income_code' =>$this->service_income_code,
            'tractor_spare_parts_code' =>$this->tractor_spare_parts_code,
            'tractor_sonalika_lub_code' =>$this->tractor_sonalika_lub_code,
            'tractor_power_oil_code' =>$this->tractor_power_oil_code,
            'nm_spare_parts_code' =>$this->nm_spare_parts_code,
            'nm_power_oil_code' =>$this->nm_power_oil_code,
            'pt_spare_parts_code' =>$this->pt_spare_parts_code,
            'pt_power_oil_code' =>$this->pt_power_oil_code,
      ];
    }
}
