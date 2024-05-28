<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobCardStoreRequest extends FormRequest{
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
//           "participant_id"=>"nullable|numeric|exists:users,id",
//           "product_id"=>"required|numeric|exists:products,id",
//           "call_type_id"=>"required|numeric|exists:call_types,id",
//           "service_type_id"=>"required|numeric|exists:service_types,id",
//           "customer_name"=>"required|max:100",
//           "customer_moblie"=>"required|max:11",
//           "buy_date"=>"nullable|date_format:d-m-Y",
//           "installation_date"=>"nullable|date_format:d-m-Y",
//           "visited_date"=>"nullable|date_format:d-m-Y",
//           "service_wanted_at"=>"nullable|date_format:d-m-Y H:i:s",
//           "service_start_at"=>"nullable|date_format:d-m-Y H:i:s",
//           "service_end_at"=>"nullable|date_format:d-m-Y H:i:s",
//           "hour"=>"nullable|numeric",
//           "service_income"=>"nullable|numeric",
//           "is_approved"=>"nullable|boolean",
//           "approver_id"=>"nullable|numeric|exists:users,id",
//           'time_app' => "required|max:100",
        ];
    }
}
