<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KpiTopicStoreRequest extends FormRequest{
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
           "name"=>"required|max:191",
           "kpi_type_id"=>"required|numeric|exists:kpi_types,id",
           "kpi_group_id"=>"required|numeric|exists:kpi_groups,id",
           "sl"=>"nullable|max:191",
           "mark"=>"nullable|numeric",
           "ach_from"=>"nullable|numeric",
           "ach_to"=>"nullable|numeric",
           "weight"=>"nullable|numeric",
           "multiplication_factor"=>"nullable|numeric",

        ];
    }
}
