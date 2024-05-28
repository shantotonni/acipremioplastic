<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Target extends JsonResource{
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
            'territory_id' =>$this->territory_id,
            'technitian_id' =>$this->technitian_id,
            'warranty_service' =>$this->warranty_service,
            'post_warranty_service' =>$this->post_warranty_service,
            'installation' =>$this->installation,
            'preodic_service' =>$this->preodic_service,
            'post_warranty_visit' =>$this->post_warranty_visit,
            'total' =>$this->total,
            'note' =>$this->note,
            'engineer_id' =>$this->engineer_id,
            'creator_id' =>$this->creator_id,
            'updater_id' =>$this->updater_id,
      ];
    }
}
