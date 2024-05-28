<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TechnicianKpiAdjustStoreRequest extends FormRequest{
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
           "date"=>"required|date_format:d-m-Y",
           "user_id"=>"required|numeric|exists:users,id",
           "service_ratio_ws_actual"=>"nullable|numeric",
           "service_ratio_pws_actual"=>"nullable|numeric",
           "satisfaction_index_six_actual"=>"nullable|numeric",
           "satisfaction_index_six_target"=>"nullable|numeric",
           "satisfaction_index_csi_actual"=>"nullable|numeric",
           "satisfaction_index_csi_target"=>"nullable|numeric",

        ];
    }
}
