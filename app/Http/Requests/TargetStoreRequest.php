<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TargetStoreRequest extends FormRequest{
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
        //    "date"=>"required|date_format:Y-m-d",
        //    "engineer_id"=>"sometimes|numeric|exists:users,id",
        //    "tractor_warranty"=>"nullable|numeric|min:0",
        //    "tractor_post_warranty"=>"nullable|numeric|min:0",
        //    "nm_warranty"=>"nullable|numeric|min:0",
        //    "nm_post_warranty"=>"nullable|numeric|min:0",
        //    "service_income_budget"=>"required|numeric|min:0",
        //    "territory_id"=>"required|numeric|exists:territories,id",
        //    "technician_id"=>"required|numeric|exists:users,id"

        //    "post_warranty_service"=>"nullable|numeric|min:0",
        //    "installation"=>"nullable|numeric|min:0",
        //    "preodic_service"=>"nullable|numeric|min:0",
        //    "post_warranty_visit"=>"nullable|numeric|min:0",
        //    "total"=>"nullable|numeric|min:0",
        //    "note"=>"nullable|max:200",
        //    "service_income" => "required|numeric|min:0",
        //    "tractor_spare_parts_lubricants"=>"required|numeric|min:0",
        //    "nm_pt_spare_parts_lubricants"=>"required|numeric|min:0"
   
        ];
    }
}
