<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TechnicianKpiAdjust extends JsonResource{
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
            'date' =>$this->date,
            'user_id' =>$this->user_id,
            'service_ratio_ws_actual' =>$this->service_ratio_ws_actual,
            'service_ratio_pws_actual' =>$this->service_ratio_pws_actual,
            'satisfaction_index_six_actual' =>$this->satisfaction_index_six_actual,
            'satisfaction_index_six_target' =>$this->satisfaction_index_six_target,
            'satisfaction_index_csi_actual' =>$this->satisfaction_index_csi_actual,
            'satisfaction_index_csi_target' =>$this->satisfaction_index_csi_target,
      ];
    }
}
