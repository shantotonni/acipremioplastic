<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VisitCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($visit){
                return [
                    'id' => $visit->id,
                    'upazilla_id' => $visit->upazilla_id,
                    'visit_type_id' => $visit->visit_type_id,
                    'result_id' => $visit->result_id,
                    'ssr_id' => $visit->result_id,
                    'ssr_name' => isset($visit->user) ? $visit->user->name : '',
                    'upazilla_name' => isset($visit->upazilla) ? $visit->upazilla->name : '',
                    'visit_type_name' => isset($visit->visit_type) ? $visit->visit_type->name : '',
                    'result_name' => isset($visit->result) ? $visit->result->name : '',
                    'village_name' => $visit->village_name,
                    'purpose' => $visit->purpose,
                    'person_name' => $visit->person_name,
                    'person_mobile' => $visit->person_mobile,
                ];
            })
        ];
    }
}
