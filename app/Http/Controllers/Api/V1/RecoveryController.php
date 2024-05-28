<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Recovery;
use Illuminate\Http\Request;

class RecoveryController extends Controller
{
    public function recovery(Request $request){
        $this->validate($request,[
           'customer_code' =>'required',
           'customer_name' =>'required',
           'mobile' =>'required',
           'territory_name' =>'required',
           'mr_number' =>'required',
           'recovery_amount' =>'required',
        ]);

        $recovery = new Recovery();
        $recovery->running_hour = $request->running_hour;
        $recovery->customer_code = $request->customer_code;
        $recovery->customer_name = $request->customer_name;
        $recovery->mobile = $request->mobile;
        $recovery->territory_name = $request->territory_name;
        $recovery->mr_number = $request->mr_number;
        $recovery->recovery_amount = $request->recovery_amount;
        $recovery->save();

        return response()->json([
           'status'=>200,
           'message'=>'Successfully added'
        ]);
    }
}
