<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TerritoryUpdateRequest extends FormRequest{
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
           "area_id"=>"required|numeric|exists:areas,id",
           "name"=>"required|max:191|unique:territories,name,$this->territory",
           "code"=>"required|max:191|unique:territories,code,$this->territory",

        ];
    }
}
