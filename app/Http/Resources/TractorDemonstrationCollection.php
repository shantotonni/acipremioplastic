<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TractorDemonstrationCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($demonstration){
                return [
                    'id' => $demonstration->id,
                    'date' => $demonstration->date,
                    'purpose_of_demo' => $demonstration->purpose_of_demo,
                    'market_type' => $demonstration->market_type,
                    'area_id' => $demonstration->area_id,
                    'area_name' => isset($demonstration->area) ? $demonstration->area->name:'',
                    'territory_id' => $demonstration->territory_id,
                    'territory_name' => isset($demonstration->territory) ? $demonstration->territory->name:'',
                    'place' => $demonstration->place,
                    'total_participant_number' => $demonstration->total_participant_number,
                    'competitord_participant_number' => $demonstration->competitord_participant_number,
                    'sales_inquiry_number' => $demonstration->sales_inquiry_number,
                    'trail_report' => $demonstration->trail_report,
                    'participant_info' => $demonstration->participant_info,
                    'model_image' => $demonstration->model_image,
                ];
            })
        ];
    }
}
