<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KpiaIncentive extends JsonResource{
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
            'kpia_id' =>$this->kpia_id,
            'incentive_factor_id' =>$this->incentive_factor_id,
            'multiplier' =>$this->multiplier,
            'tractor' =>$this->tractor,
            'nmpt' =>$this->nmpt,
            'tractor_and_nmpt' =>$this->tractor_and_nmpt,
      ];
    }
}
