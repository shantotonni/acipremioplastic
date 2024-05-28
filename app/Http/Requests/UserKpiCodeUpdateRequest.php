<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserKpiCodeUpdateRequest extends FormRequest{
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
           "user_id"=>"required|numeric|exists:users,id|unique:user_kpi_codes,user_id,$this->user_kpi_code",
           "service_income_code"=>"nullable|max:191",
           "tractor_spare_parts_code"=>"nullable|max:191",
           "tractor_sonalika_lub_code"=>"nullable|max:191",
           "tractor_power_oil_code"=>"nullable|max:191",
           "nm_spare_parts_code"=>"nullable|max:191",
           "nm_power_oil_code"=>"nullable|max:191",
           "pt_spare_parts_code"=>"nullable|max:191",
           "pt_power_oil_code"=>"nullable|max:191",

        ];
    }
}
