<?php

namespace App\Http\Controllers\Api\V1;

use App\Customer;
use App\CustomerToken;
use App\Http\Controllers\Controller;
use App\Http\Resources\TractorServiceDetailsResource;
use App\ServiseDetails;
use Illuminate\Http\Request;

class TractorServiceController extends Controller
{
    public function __construct(){
        // $this->middleware('auth');
    }

    public function findService(Request $request){
        $service_hour = $request->service_hour;
        $token = str_replace("token ","",$request->header('Authorization'));
        $customer_token = CustomerToken::where('token',$token)->first();
        $customer = Customer::find($customer_token->customer_id);
        $customer->service_hour = $service_hour;
        $customer->save();

        $service_details = ServiseDetails::with('servicing_type')
            ->where('from_hr','<=',$service_hour)
            ->where('to_hr','>=',$service_hour)
            ->get();

        return TractorServiceDetailsResource::collection($service_details);

    }
}
