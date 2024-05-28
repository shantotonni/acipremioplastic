<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallTypeUpdateRequest extends FormRequest{
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
           "name"=>"required|max:191|unique:call_types,name,$this->call_type",
           "name_bn"=>"required|max:191|unique:call_types,name_bn,$this->call_type",
           "code"=>"required|max:191|unique:call_types,code,$this->call_type",

        ];
    }
}
