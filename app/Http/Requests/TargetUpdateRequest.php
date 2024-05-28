<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TargetUpdateRequest extends FormRequest{
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
           "territory_id"=>"required|numeric|exists:territories,id",
           "technitian_id"=>"required|numeric|exists:users,id",
           "warranty_service"=>"nullable|numeric",
           "post_warranty_service"=>"nullable|numeric",
           "installation"=>"nullable|numeric",
           "preodic_service"=>"nullable|numeric",
           "post_warranty_visit"=>"nullable|numeric",
           "total"=>"nullable|numeric",
           "note"=>"nullable|max:200",
           "engineer_id"=>"required|numeric|exists:users,id",
           "service_income" => "required|numeric|min:0",
           "tractor_spare_parts_lubricants"=>"required|numeric|min:0",
           "nm_pt_spare_parts_lubricants"=>"required|numeric|min:0"

        ];
    }
}
