<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserKpiDetailUpdateRequest extends FormRequest{
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
           "kpi_topic_id"=>"required|numeric|exists:kpi_topics,id",
           "target"=>"nullable|numeric",
           "actual"=>"nullable|numeric",
           "weight"=>"nullable|numeric",
           "score"=>"nullable|numeric",
           "f_score"=>"nullable|numeric",

        ];
    }
}
