<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceTypeUpdateRequest extends FormRequest{
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
           "name"=>"required|max:191|unique:service_types,name,$this->service_type",
           "name_bn"=>"required|max:191|unique:service_types,name_bn,$this->service_type",
           "code"=>"required|max:191|unique:service_types,code,$this->service_type",

        ];
    }
}
