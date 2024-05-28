<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' =>$this->id,
            'product_id' =>$this->product_id,
            'model_name' =>$this->model_name,
            'model_name_bn' =>$this->model_name_bn,
            'model_code' =>$this->model_code,
            'details' =>$this->details,
            'model_image' =>url('product_image/').'/'.$this->model_image,
      ];
    }
}
