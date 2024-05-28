<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class RegisteredCustomer extends JsonResource
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
            "name"=> $this->name,
            "code"=> $this->code,
            "product_id"=> $this->product_id,
            "date_of_purchase"=> $this->date_of_purchase,
            "address"=>$this->address,
            "mobile"=>$this->mobile,
            "chassis"=>$this->chassis,
            "area_id"=>$this->area_id,
            "model_id"=>$this->model_id,
            "image"=>URL::to('/').'/product_image/'.$this->image,
            "chassis_image"=>URL::to('/').'/chassisimage/'.$this->chassis_image
        ];
    }
}
