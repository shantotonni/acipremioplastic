<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KpiaIncentiveUpdateRequest extends FormRequest{
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
           "kpia_id"=>"required|numeric|exists:kpia,id",
           "incentive_factor_id"=>"required|numeric|exists:incentive_factors,id",
           "multiplier"=>"nullable|numeric",
           "tractor"=>"nullable|numeric",
           "nmpt"=>"nullable|numeric",
           "tractor_and_nmpt"=>"nullable|numeric",

        ];
    }
}
