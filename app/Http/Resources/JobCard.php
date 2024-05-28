<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobCard extends JsonResource{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request){
        return [
            'id' =>$this->id,
            'territory_id' =>$this->territory_id,
            'district_id'=>(int)$this->district_id,
            'upazila_id'=>(int)$this->upazila_id,
            'area_id' =>(int)$this->area_id,
            'area' =>isset($this->area) ? $this->area->name : '',
            'engineer_id' => $this->engineer_id,
            'engineer' =>isset($this->engineer) ? $this->engineer->name : '',
            'technitian' =>isset($this->technitian) ? $this->technitian->name : '',
            'creator_name' =>$this->job_creator,
            'participant_id' =>$this->participant_id,
            'product' =>isset($this->product) ? $this->product->name_bn : '',
            'product_id' =>$this->product_id,
            'model_name' =>isset($this->model) ? $this->model->model_name_bn:'',
            'model_id' =>$this->model_id,
            'call_type' =>isset($this->call_type) ? $this->call_type->name : '',
            'call_type_id' =>$this->call_type_id,
            'service_type_id' =>$this->service_type_id,
            'service_type' =>isset($this->service_type) ? $this->service_type->name : '',
            'customer_name' =>$this->customer_name,
            'customer_moblie' =>$this->customer_moblie,
            'buy_date' => $this->buy_date ? date('d-m-Y',strtotime($this->buy_date)) : null,
            'visited_date' => $this->visited_date ? date('d-m-Y',strtotime($this->visited_date)) : null,
            'installation_date' => $this->installation_date ? date('d-m-Y',strtotime($this->installation_date)) : null,
            'service_wanted_at' => $this->service_wanted_at ? date('d-m-Y H:i:s',strtotime($this->service_wanted_at)) : null,
            'service_start_at' =>$this->service_start_at ? date('d-m-Y H:i:s',strtotime($this->service_start_at)) : null,
            'service_end_at' =>$this->service_end_at ? date('d-m-Y H:i:s',strtotime($this->service_end_at)) : null,
            'hour' =>$this->hour,
            'service_income' =>$this->service_income,
            'address' =>$this->address,
            'is_approved' =>$this->is_approved,
            'is_due' =>$this->is_due,
            'approver_id' =>$this->approver_id,
            'rating' => $this->rating,
            'job_status' => $this->job_status,
            'chassis_number' => $this->chassis_number,
            'running_houre' => $this->running_houre,
            'section_id' => $this->section_id,
            'created_at' => $this->created_at ? date('d-m-Y H:i:s',strtotime($this->created_at)) : null,
            'time_app' => $this->time_app,
            'service_date'=> $this->service_date ? date('d-m-Y',strtotime($this->service_date)) : null,
            'remarks'=>$this->remarks,
            'spare_parts_sale'=>$this->spare_parts_sale,
            'invoice_number'=>$this->invoice_number,
            'total_service_cost'=>$this->total_service_cost,
            'discount_amount'=>$this->discount_amount,
            'total_receviable'=>$this->total_receviable,
            'service_income_details' => new ServiceIncomeDetailsCollection($this->service_income_details),
            'service_income_amount' => $this->service_income_amount,
        ];
    }
}
 