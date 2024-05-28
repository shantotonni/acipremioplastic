<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobCardUpdateRequest extends FormRequest{

    public function authorize(){
        return true;
    }

    public function rules(){
        return [
           "territory_id"=>"required|numeric|exists:territories,id",
           "area_id"=>"required|numeric|exists:areas,id",
           "engineer_id"=>"required|numeric|exists:users,id",
           "technitian_id"=>"required|numeric|exists:users,id",
           "participant_id"=>"nullable|numeric|exists:users,id",
           "product_id"=>"required|numeric|exists:products,id",
           "call_type_id"=>"required|numeric|exists:call_types,id",
           "service_type_id"=>"required|numeric|exists:service_types,id",
           "customer_name"=>"required|max:100",
           "customer_moblie"=>"required|max:11",
           "buy_date"=>"nullable|date_format:d-m-Y",
           "installation_date"=>"nullable|date_format:d-m-Y",
           "visited_date"=>"nullable|date_format:d-m-Y",
           "service_wanted_at"=>"nullable|date_format:d-m-Y H:i:s",
           "service_start_at"=>"nullable|date_format:d-m-Y H:i:s",
           "service_end_at"=>"nullable|date_format:d-m-Y H:i:s",
           "hour"=>"required|numeric",
           "service_income"=>"nullable|numeric",
           "is_approved"=>"nullable|boolean",
           "approver_id"=>"nullable|numeric|exists:users,id",

        ];
    }
}
