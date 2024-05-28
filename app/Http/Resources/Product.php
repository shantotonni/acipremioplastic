<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource{
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
            'name_bn' =>$this->name_bn,
            'code' =>$this->code,
            'details' =>$this->details,
            'product_image' =>url('product_image/').'/'.$this->product_image,
      ];
    }
}
