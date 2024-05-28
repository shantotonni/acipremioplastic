<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WeightUpdateRequest extends FormRequest{
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
           "kpi_type_id"=>"required|numeric|exists:kpi_types,id|unique:weights,kpi_type_id,$this->weight",
           "service_ratio_ws_weight"=>"nullable|numeric",
           "service_ratio_pws_weight"=>"nullable|numeric",
           "satisfaction_index_six_weight"=>"nullable|numeric",
           "satisfaction_index_csi_weight"=>"nullable|numeric",
           "service_income_weight"=>"nullable|numeric",
           "report_submission_weight"=>"nullable|numeric",
           "app_monitor_weight"=>"nullable|numeric",
           "team_co_weight"=>"nullable|numeric",
           "sp_tractor_weight"=>"nullable|numeric",
           "sp_nmpt_weight"=>"nullable|numeric",
           "sp_tractor_plus_nmpt_weight"=>"nullable|numeric",

        ];
    }
}
