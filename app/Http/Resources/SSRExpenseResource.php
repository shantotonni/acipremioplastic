<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SSRExpenseResource extends JsonResource
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
            'user_id' =>$this->user_id,
            'opening_km' =>$this->opening_km,
            'closing_km' =>$this->closing_km,
            'bike_no' =>$this->bike_no,
            'period' =>$this->period,
            'opening_image' =>url('/ssr_expense').'/'.$this->opening_image,
            'closing_image' => isset($this->closing_image) ? url('/ssr_expense').'/'.$this->closing_image : null,
            'is_approved' =>$this->is_approved,
        ];
    }
}
