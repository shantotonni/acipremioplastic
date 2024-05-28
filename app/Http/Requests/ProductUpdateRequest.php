<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest{
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
           "name"=>"required|max:191|unique:products,name,$this->product",
           "name_bn"=>"required|max:191|unique:products,name_bn,$this->product",
           "code"=>"required|max:191|unique:products,code,$this->product",

        ];
    }
}
