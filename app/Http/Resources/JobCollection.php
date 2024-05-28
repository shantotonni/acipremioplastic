<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class JobCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' =>$this->id,
            'territory_id' =>$this->territory_id,
            'area_id' =>$this->area_id,
            'engineer_id' =>$this->engineer_id,
            'technitian_id' =>$this->technitian_id,
            'creator_name' =>$this->technitian->name,
            'participant_id' =>$this->participant_id,
            'product_id' =>$this->product_id,
            'call_type_id' =>$this->call_type_id,
            'service_type' =>$this->service_type->name,
            'customer_name' =>$this->customer_name,
            'customer_moblie' =>$this->customer_moblie,
            'buy_date' => $this->buy_date ? date('d-m-Y',strtotime($this->buy_date)) : null,
            'visited_date' => $this->visited_date ? date('d-m-Y',strtotime($this->visited_date)) : null,
            'installation_date' => $this->installation_date ? date('d-m-Y',strtotime($this->installation_date)) : null,
            'service_wanted_at' => $this->service_wanted_at ? date('d-m-Y H:i:s',strtotime($this->service_wanted_at)) : null,
            'service_start_at' =>$this->service_start_at ? date('d-m-Y H:i:s',strtotime($this->service_start_at)) : null,
            'service_end_at' =>$this->service_start_at ? date('d-m-Y H:i:s',strtotime($this->service_end_at)) : null,
            'hour' =>$this->hour,
            'service_income' =>$this->service_income,
            'is_approved' =>$this->is_approved,
            'approver_id' =>$this->approver_id,
            'rating' => $this->rating,
            'created_at' => $this->created_at ? date('d-m-Y H:i:s',strtotime($this->created_at)) : null,
            'time_app' => $this->time_app,
            'service_date'=> $this->service_date ? date('d-m-Y',strtotime($this->service_date)) : null,
        ];
    }
}
