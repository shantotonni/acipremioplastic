<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceIncomeDetailsCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($details){
                return [
                    'id' => $details->id,
                    'job_card_id' => $details->job_card_id,
                    'service_category_id' => $details->service_category_id,
                    'service_category' => isset($details->service_category) ? $details->service_category->name:'',
                    'service_amount' => $details->service_amount,
                ];
            })
        ];
    }
}
