<?php

namespace App\Http\Controllers\Api\V1;

use App\Area;
use App\Customer;
use App\CustomerToken;
use App\District;
use App\Http\Controllers\Controller;
use App\ServiceRequest;
use App\Upazila;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CustomerRequestCreateController extends Controller
{
    public function store(Request $request){

        $token = str_replace("token ","",$request->header('Authorization'));
        $customer_token = CustomerToken::where("token",$token)->first();
        if(!$customer_token){ return response()->json(['error'=>"Unauthorized"],401);}

        $this->validate($request,[
            "district_id"=>"required|numeric",
            "upazila_id"=>"required|numeric",
            "product_id"=>"required|numeric",
//            "section_id"=>"required|numeric",
            "remarks"=>"nullable|max:200",
        ]);

        $area_i = District::where('id', $request->district_id)->first();
        $area = Area::find($area_i->area_id);
        $upazila = Upazila::query()->where('district_id',$area_i->id)->first();
        $upazilaName = $upazila->name;

        $customer = Customer::where('id',$customer_token->customer_id)->first();
        $service_request = new ServiceRequest;
        $service_request->district_id = $request->district_id;
        $service_request->upazila_id = $request->upazila_id;
        $service_request->service_type_id = null;
        $service_request->call_type_id = 1;
        $service_request->area_id = $area->id;
        $service_request->section_id = $request->section_id;
        $service_request->product_id = $request->product_id;
        $service_request->remarks = $request->remarks;
        $service_request->customer_id = $customer_token->customer_id;
        $service_request->address = $customer->address;
        $service_request->customer_name = $customer->name;
        $service_request->customer_mobile = $customer->mobile;
        $service_request->chassis_number = $customer->chassis;
        $service_request->lat = $request->lat;
        $service_request->lon = $request->lon;
        $service_request->request_creator = 'customer';

        // $area = Area::find($customer->area_id);
        
        $user = User::where('id',$area->engineer->user_id)->first();

        if($area->engineer){
            $service_request->engineer_id = $user->id;
        }else{
            $service_request->engineer_id = 2;
        }
        $service_request->service_requested_at = Carbon::now();
        $service_request->created_at = Carbon::now();
        $service_request->save();

        //send otp for technician verification
        $smscontent = "$customer->name".' আপনার কাছে একটি সার্ভিস চেয়েছে,উপজেলা '.$upazilaName.' চেক করুন।';
        $mobileno = $user->mobile;

        $respons = $this->sendsms($ip = '192.168.100.213', $userid = 'motors', $password = 'Asdf1234', $smstext = urlencode($smscontent), $receipient = urlencode($mobileno));

        return response()->json([
           'status' => 1,
           'msg' => 'Successfully Send Request'
        ]);
    }

    public function sendsms($ip, $userid, $password, $smstext, $receipient) {
        $smsUrl = "http://{$ip}/httpapi/sendsms?userId={$userid}&password={$password}&smsText=" . $smstext . "&commaSeperatedReceiverNumbers=" . $receipient;
        //echo $smsUrl; exit();
        $response = file_get_contents($smsUrl);
        //print_r($response); exit();
        return json_decode($response);
    }
}
