<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProduct extends JsonResource
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
            'order_id' =>$this->order_id,
            'product_id' =>$this->product_id,
            'product_name' =>$this->product_name,
            'quantity' =>$this->quantity,
            'item_price' =>$this->item_price,
            'item_final_price' =>$this->item_final_price,
            'image' =>url('/part_image').'/'.$this->part->image,
            'created_at' =>$this->created_at,
        ];
    }
}
