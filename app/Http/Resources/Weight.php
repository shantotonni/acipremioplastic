<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Weight extends JsonResource{
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
            'kpi_type_id' =>$this->kpi_type_id,
            'service_ratio_ws_weight' =>$this->service_ratio_ws_weight,
            'service_ratio_pws_weight' =>$this->service_ratio_pws_weight,
            'satisfaction_index_six_weight' =>$this->satisfaction_index_six_weight,
            'satisfaction_index_csi_weight' =>$this->satisfaction_index_csi_weight,
            'service_income_weight' =>$this->service_income_weight,
            'report_submission_weight' =>$this->report_submission_weight,
            'app_monitor_weight' =>$this->app_monitor_weight,
            'team_co_weight' =>$this->team_co_weight,
            'sp_tractor_weight' =>$this->sp_tractor_weight,
            'sp_nmpt_weight' =>$this->sp_nmpt_weight,
            'sp_tractor_plus_nmpt_weight' =>$this->sp_tractor_plus_nmpt_weight,
      ];
    }
}
