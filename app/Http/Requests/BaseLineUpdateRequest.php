<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseLineUpdateRequest extends FormRequest{
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
           "kpi_type_id"=>"required|numeric|exists:kpi_types,id|unique:base_lines,kpi_type_id,$this->base_line",
           "service_income_base_line"=>"nullable|numeric",
           "sp_tractor_base_line"=>"nullable|numeric",
           "sp_nmpt_base_line"=>"nullable|numeric",
           "sp_tractor_plus_nmpt_base_line"=>"nullable|numeric",

        ];
    }
}
