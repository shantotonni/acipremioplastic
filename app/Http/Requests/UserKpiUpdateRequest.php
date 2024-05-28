<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserKpiUpdateRequest extends FormRequest{
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
           "kpi_type_id"=>"required|numeric|exists:kpi_types,id",
           "total_kpi_target"=>"required|numeric",
           "total_kpi_ach"=>"required|numeric",

        ];
    }
}
