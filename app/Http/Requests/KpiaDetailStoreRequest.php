<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KpiaDetailStoreRequest extends FormRequest{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
           "kpia_id"=>"required|numeric|exists:kpia,id",
           "service_ratio_ws_target"=>"nullable|numeric",
           "service_ratio_ws_actual"=>"nullable|numeric",
           "service_ratio_ws_weight"=>"nullable|numeric",
           "service_ratio_ws_score"=>"nullable|numeric",
           "service_ratio_ws_f_score"=>"nullable|numeric",
           "service_ratio_pws_target"=>"nullable|numeric",
           "service_ratio_pws_actual"=>"nullable|numeric",
           "service_ratio_pws_weight"=>"nullable|numeric",
           "service_ratio_pws_score"=>"nullable|numeric",
           "service_ratio_pws_f_score"=>"nullable|numeric",
           "satisfaction_index_six_target"=>"nullable|numeric",
           "satisfaction_index_six_actual"=>"nullable|numeric",
           "satisfaction_index_six_weight"=>"nullable|numeric",
           "satisfaction_index_six_score"=>"nullable|numeric",
           "satisfaction_index_six_f_score"=>"nullable|numeric",
           "satisfaction_index_csi_target"=>"nullable|numeric",
           "satisfaction_index_csi_actual"=>"nullable|numeric",
           "satisfaction_index_csi_weight"=>"nullable|numeric",
           "satisfaction_index_csi_score"=>"nullable|numeric",
           "satisfaction_index_csi_f_score"=>"nullable|numeric",
           "service_income_target"=>"nullable|numeric",
           "service_income_actual"=>"nullable|numeric",
           "service_income_weight"=>"nullable|numeric",
           "service_income_score"=>"nullable|numeric",
           "service_income_f_score"=>"nullable|numeric",
           "report_submission_weight"=>"nullable|numeric",
           "report_submission_score"=>"nullable|numeric",
           "report_submission_f_score"=>"nullable|numeric",
           "app_monitor_weight"=>"nullable|numeric",
           "app_monitor_score"=>"nullable|numeric",
           "app_monitor_f_score"=>"nullable|numeric",
           "team_co_weight"=>"nullable|numeric",
           "team_co_score"=>"nullable|numeric",
           "team_co_f_score"=>"nullable|numeric",
           "service_income_base_line"=>"nullable|numeric",
           "service_f_score_total"=>"nullable|numeric",
           "service_f_score_percent"=>"nullable|numeric",
           "service_income_total_incentive"=>"nullable|numeric",
           "sp_tractor_target"=>"nullable|numeric",
           "sp_tractor_actual"=>"nullable|numeric",
           "sp_tractor_weight"=>"nullable|numeric",
           "sp_tractor_score"=>"nullable|numeric",
           "sp_tractor_f_score"=>"nullable|numeric",
           "sp_tractor_base_line"=>"nullable|numeric",
           "sp_tractor_f_score_total"=>"nullable|numeric",
           "sp_tractor_f_score_percent"=>"nullable|numeric",
           "sp_tractor_total_incentive"=>"nullable|numeric",
           "sp_nmpt_target"=>"nullable|numeric",
           "sp_nmpt_actual"=>"nullable|numeric",
           "sp_nmpt_weight"=>"nullable|numeric",
           "sp_nmpt_score"=>"nullable|numeric",
           "sp_nmpt_f_score"=>"nullable|numeric",
           "sp_nmpt_base_line"=>"nullable|numeric",
           "sp_nmpt_f_score_total"=>"nullable|numeric",
           "sp_nmpt_f_score_percent"=>"nullable|numeric",
           "sp_nmpt_total_incentive"=>"nullable|numeric",
           "sp_tractor_plus_nmpt_target"=>"nullable|numeric",
           "sp_tractor_plus_nmpt_actual"=>"nullable|numeric",
           "sp_tractor_plus_nmpt_weight"=>"nullable|numeric",
           "sp_tractor_plus_nmpt_score"=>"nullable|numeric",
           "sp_tractor_plus_nmpt_f_score"=>"nullable|numeric",
           "sp_tractor_plus_nmpt_base_line"=>"nullable|numeric",
           "sp_tractor_plus_nmpt_f_score_total"=>"nullable|numeric",
           "sp_tractor_plus_nmpt_f_score_percent"=>"nullable|numeric",
           "sp_tractor_plus_nmpt_total_incentive"=>"nullable|numeric",
           "incentive_101_115_mul"=>"nullable|numeric",
           "incentive_116_140_mul"=>"nullable|numeric",
           "incentive_141_above_mul"=>"nullable|numeric",
           "incentive_101_115_amount"=>"nullable|numeric",
           "incentive_116_140_amount"=>"nullable|numeric",
           "incentive_141_above_amount"=>"nullable|numeric",

        ];
    }
}
