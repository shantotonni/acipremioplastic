<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncentiveFactorStoreRequest extends FormRequest{
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
           "kpi_type_id"=>"required|numeric|exists:kpi_types,id",
           "name"=>"required|max:191|unique:incentive_factors",
           "from"=>"required|numeric",
           "to"=>"required|numeric",
           "multiplication_factor"=>"required|numeric",

        ];
    }
}
