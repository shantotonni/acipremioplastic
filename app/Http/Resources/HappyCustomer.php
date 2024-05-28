<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HappyCustomer extends JsonResource
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
            'customer_name' =>$this->customer_name,
            'customer_mobile' =>$this->customer_mobile,
            'address' =>$this->address,
            'area' =>$this->area->name,
            'video_url' =>$this->video_url,
            'thumbnail_image' =>url('thumbnail_image/').'/'.$this->thumbnail_image,
        ];
    }
}
