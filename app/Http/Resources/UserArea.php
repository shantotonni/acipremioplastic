<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserArea extends JsonResource{
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
            'user_id' =>$this->user_id,
      ];
    }
}
