<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Territory extends JsonResource{
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
            'area_id' =>$this->area_id,
            'name' =>$this->name,
            'code' =>$this->code,
      ];
    }
}
