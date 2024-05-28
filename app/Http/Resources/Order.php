<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' =>$this->id,
            'name' =>$this->name,
            'mobile' =>$this->mobile,
            'customer_address' =>$this->customer_address,
            'delivery_address' =>$this->delivery_address,
            'area_name' =>$this->area_name,
            'district_name' =>$this->district_name,
            'upazila_name' =>$this->upazila_name,
            'total_amount' =>$this->total_amount,
            'discount' =>$this->discount,
            'delivery_charge' =>$this->delivery_charge,
            'grand_total' =>$this->grand_total,
            'order_status' =>$this->order_status,
            'created_at' =>$this->created_at,
            'customer_id' =>$this->customer_id,
            'orderProduct'=> OrderProduct::collection($this->orderProduct)
        ];
    }
}
