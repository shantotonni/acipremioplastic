<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserKpiDetail extends JsonResource{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request){
        //return parent::toArray($request);
        return [
            'id' =>$this->id,
            'user_kpi_id' =>$this->user_kpi_id,
            'kpi_topic_id' =>$this->kpi_topic_id,
            'target' =>$this->target,
            'actual' =>$this->actual,
            'weight' =>$this->weight,
            'score' =>$this->score,
            'f_score' =>$this->f_score,
      ];
    }
}
