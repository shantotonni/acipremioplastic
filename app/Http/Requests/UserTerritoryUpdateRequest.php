<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserTerritoryUpdateRequest extends FormRequest{
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
           "territory_id"=>"required|numeric|exists:territories,id",
           "user_id"=>"required|numeric|exists:users,id|unique:user_territories,user_id,$this->user_territory",

        ];
    }
}
