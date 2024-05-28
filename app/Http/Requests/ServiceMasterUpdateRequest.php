<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceMasterUpdateRequest extends FormRequest{
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
           "name"=>"required|max:100|unique:service_masters,name,$this->service_master",
           "code"=>"required|max:100|unique:service_masters,code,$this->service_master",

        ];
    }
}
