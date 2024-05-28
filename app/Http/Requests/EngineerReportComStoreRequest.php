<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EngineerReportComStoreRequest extends FormRequest{
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
           "report_actual"=>"required|numeric",
           "app_dashboard_actual"=>"required|numeric",
           "team_coordination_actual"=>"required|numeric",

        ];
    }
}
