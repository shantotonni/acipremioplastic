<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserKpiBaseLineStoreRequest extends FormRequest{
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
           "user_kpi_id"=>"required|numeric|exists:user_kpis,id",
           "kpi_group_id"=>"required|numeric|exists:kpi_groups,id",
           "amount"=>"required|numeric",

        ];
    }
}
