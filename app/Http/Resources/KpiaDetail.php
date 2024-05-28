<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KpiaDetail extends JsonResource{
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
            'kpia_id' =>$this->kpia_id,
            'service_ratio_ws_target' =>$this->service_ratio_ws_target,
            'service_ratio_ws_actual' =>$this->service_ratio_ws_actual,
            'service_ratio_ws_weight' =>$this->service_ratio_ws_weight,
            'service_ratio_ws_score' =>$this->service_ratio_ws_score,
            'service_ratio_ws_f_score' =>$this->service_ratio_ws_f_score,
            'service_ratio_pws_target' =>$this->service_ratio_pws_target,
            'service_ratio_pws_actual' =>$this->service_ratio_pws_actual,
            'service_ratio_pws_weight' =>$this->service_ratio_pws_weight,
            'service_ratio_pws_score' =>$this->service_ratio_pws_score,
            'service_ratio_pws_f_score' =>$this->service_ratio_pws_f_score,
            'satisfaction_index_six_target' =>$this->satisfaction_index_six_target,
            'satisfaction_index_six_actual' =>$this->satisfaction_index_six_actual,
            'satisfaction_index_six_weight' =>$this->satisfaction_index_six_weight,
            'satisfaction_index_six_score' =>$this->satisfaction_index_six_score,
            'satisfaction_index_six_f_score' =>$this->satisfaction_index_six_f_score,
            'satisfaction_index_csi_target' =>$this->satisfaction_index_csi_target,
            'satisfaction_index_csi_actual' =>$this->satisfaction_index_csi_actual,
            'satisfaction_index_csi_weight' =>$this->satisfaction_index_csi_weight,
            'satisfaction_index_csi_score' =>$this->satisfaction_index_csi_score,
            'satisfaction_index_csi_f_score' =>$this->satisfaction_index_csi_f_score,
            'service_income_target' =>$this->service_income_target,
            'service_income_actual' =>$this->service_income_actual,
            'service_income_weight' =>$this->service_income_weight,
            'service_income_score' =>$this->service_income_score,
            'service_income_f_score' =>$this->service_income_f_score,
            'report_submission_weight' =>$this->report_submission_weight,
            'report_submission_score' =>$this->report_submission_score,
            'report_submission_f_score' =>$this->report_submission_f_score,
            'app_monitor_weight' =>$this->app_monitor_weight,
            'app_monitor_score' =>$this->app_monitor_score,
            'app_monitor_f_score' =>$this->app_monitor_f_score,
            'team_co_weight' =>$this->team_co_weight,
            'team_co_score' =>$this->team_co_score,
            'team_co_f_score' =>$this->team_co_f_score,
            'service_income_base_line' =>$this->service_income_base_line,
            'service_f_score_total' =>$this->service_f_score_total,
            'service_f_score_percent' =>$this->service_f_score_percent,
            'service_income_total_incentive' =>$this->service_income_total_incentive,
            'sp_tractor_target' =>$this->sp_tractor_target,
            'sp_tractor_actual' =>$this->sp_tractor_actual,
            'sp_tractor_weight' =>$this->sp_tractor_weight,
            'sp_tractor_score' =>$this->sp_tractor_score,
            'sp_tractor_f_score' =>$this->sp_tractor_f_score,
            'sp_tractor_base_line' =>$this->sp_tractor_base_line,
            'sp_tractor_f_score_total' =>$this->sp_tractor_f_score_total,
            'sp_tractor_f_score_percent' =>$this->sp_tractor_f_score_percent,
            'sp_tractor_total_incentive' =>$this->sp_tractor_total_incentive,
            'sp_nmpt_target' =>$this->sp_nmpt_target,
            'sp_nmpt_actual' =>$this->sp_nmpt_actual,
            'sp_nmpt_weight' =>$this->sp_nmpt_weight,
            'sp_nmpt_score' =>$this->sp_nmpt_score,
            'sp_nmpt_f_score' =>$this->sp_nmpt_f_score,
            'sp_nmpt_base_line' =>$this->sp_nmpt_base_line,
            'sp_nmpt_f_score_total' =>$this->sp_nmpt_f_score_total,
            'sp_nmpt_f_score_percent' =>$this->sp_nmpt_f_score_percent,
            'sp_nmpt_total_incentive' =>$this->sp_nmpt_total_incentive,
            'sp_tractor_plus_nmpt_target' =>$this->sp_tractor_plus_nmpt_target,
            'sp_tractor_plus_nmpt_actual' =>$this->sp_tractor_plus_nmpt_actual,
            'sp_tractor_plus_nmpt_weight' =>$this->sp_tractor_plus_nmpt_weight,
            'sp_tractor_plus_nmpt_score' =>$this->sp_tractor_plus_nmpt_score,
            'sp_tractor_plus_nmpt_f_score' =>$this->sp_tractor_plus_nmpt_f_score,
            'sp_tractor_plus_nmpt_base_line' =>$this->sp_tractor_plus_nmpt_base_line,
            'sp_tractor_plus_nmpt_f_score_total' =>$this->sp_tractor_plus_nmpt_f_score_total,
            'sp_tractor_plus_nmpt_f_score_percent' =>$this->sp_tractor_plus_nmpt_f_score_percent,
            'sp_tractor_plus_nmpt_total_incentive' =>$this->sp_tractor_plus_nmpt_total_incentive,
            'incentive_101_115_mul' =>$this->incentive_101_115_mul,
            'incentive_116_140_mul' =>$this->incentive_116_140_mul,
            'incentive_141_above_mul' =>$this->incentive_141_above_mul,
            'incentive_101_115_amount' =>$this->incentive_101_115_amount,
            'incentive_116_140_amount' =>$this->incentive_116_140_amount,
            'incentive_141_above_amount' =>$this->incentive_141_above_amount,
      ];
    }
}
