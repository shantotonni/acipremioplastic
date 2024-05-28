<?php

namespace App\Http\Controllers\Api\V1;

use App\Area;
use App\Customer;
use App\CustomerToken;
use App\District;
use App\Http\Controllers\Controller;
use App\JobCard;
use App\JobCardDetail;
use App\Otp;
use App\ServiceRequest;
use App\Territory;
use App\User;
use App\UserArea;
use App\UserTerritory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\JobCard as JobCardResource;
use App\ProductModel;
use App\UserToken;
use Illuminate\Support\Facades\DB;

class EngineerCreateRequestController extends Controller
{
    public function engineerServiceRequestList(Request $request){
        $service_request = ServiceRequest::where('engineer_id',$request->engineer_id)->where('is_agree',null)->orderBy('id','desc')->get();
        return response()->json([
            'status' => 1,
            'service_request' => $service_request
        ]);
    }

    public function engineerJobList(Request $request){
        
        $job_cards = JobCard::query();

        if($request->has('chassis_number') && ($request->chassis_number != NULL)){
            $job_cards = $job_cards->where('chassis_number', $request->chassis_number);
        }else{
            $job_cards = $job_cards->where('engineer_id',$request->engineer_id);
        }
        $job_cards = $job_cards->orderBy('id','desc')->paginate(10);
        return JobCardResource::collection($job_cards);
    }

    public function createRequest(Request $request){

        $this->validate($request,[
            "customer_name"=>"required",
            "customer_mobile"=>"required",
            "technitian_id"=>"required|numeric",
        ]);

        $area_i = District::where('id', $request->district_id)->first();
        $user_area = UserArea::where('user_id',$request->engineer_id)->first();
        //$area = Area::find($area_i->area_id);

        $technician_id = $request->technitian_id;
        $user_territory = UserTerritory::where('user_id',$technician_id)->first();
        if(!$user_territory){return response()->json(['error'=>"No Territory Defined for this user"],422);}
        $territory = Territory::find($user_territory->territory_id);

        $job_card = new JobCard();
        $job_card->service_type_id = null;
        $job_card->call_type_id = 1;
        $job_card->customer_name = $request->customer_name;
        $job_card->customer_moblie = $request->customer_mobile;
        $job_card->technitian_id = $request->technitian_id;
        $job_card->engineer_id = $request->engineer_id;
        $job_card->area_id = $user_area->area_id;
        $job_card->district_id = $request->district_id;
        $job_card->upazila_id = $request->upazila_id;
        $job_card->territory_id = $territory->id;
        $job_card->job_creator = 'engineer';
        $job_card->service_wanted_at = date("Y-m-d H:i:s",strtotime($request->service_wanted_at));
        $job_card->service_date = date("Y-m-d",strtotime($request->service_wanted_at));
        $job_card->created_at = Carbon::now();

        $job_card->save();
        $job_card->job_card_no = 'MS'.$job_card->id;

        if ($job_card->save()) {
            $technician = User::where('id',$request->technitian_id)->first();
            //send otp for technician verification
            $unique_code = mt_rand(1000,9999);
            $smscontent = 'সার্ভিস রিকুয়েস্ট জব কার্ড নম্বর '."$unique_code".'. টেকনিশিয়ানকে সার্ভিস শুরু করার পূর্বে প্রদত্ত জব কার্ড নম্বর প্রদান করুন। আপনার টেকনিশিয়ান হচ্ছে '."$technician->name($technician->mobile)";
            $mobileno = $job_card->customer_moblie;

            $respons = $this->sendsms($ip = '192.168.100.213', $userid = 'motors', $password = 'Asdf1234', $smstext = urlencode($smscontent), $receipient = urlencode($mobileno));

            $otp = new Otp();
            $otp->job_card_id = $job_card->id;
            $otp->otp_code = $unique_code;
            $otp->mobile = $mobileno;
            $otp->status = 0;
            $otp->save();

            //send notification to technician
            $res = $this->notifyUser($request->technitian_id);
        }

        $this->saveJobCardDetail($job_card->id,$request->technitian_id);

        return response()->json([
            'status' => 1,
            'msg' => 'Successfully Send Request'
        ]);
    }

    public function resendOTP(Request $request)
    {
        $job_card = JobCard::where('id', $request->id)->first();
        $technician = User::where('id',$job_card->technitian_id)->first();
        $unique_code = mt_rand(1000,9999);
        $smscontent = 'সার্ভিস রিকুয়েস্ট জব কার্ড নম্বর '."$unique_code".'. টেকনিশিয়ানকে সার্ভিস শুরু করার পূর্বে প্রদত্ত জব কার্ড নম্বর প্রদান করুন। আপনার টেকনিশিয়ান হচ্ছে '."$technician->name($technician->mobile)";
        $mobileno = $job_card->customer_moblie;

        $respons = $this->sendsms($ip = '192.168.100.213', $userid = 'motors', $password = 'Asdf1234', $smstext = urlencode($smscontent), $receipient = urlencode($mobileno));

        $otp = new Otp();
        $otp->job_card_id = $job_card->id;
        $otp->otp_code = $unique_code;
        $otp->mobile = $mobileno;
        $otp->status = 0;
        $otp->save();

        //send notification to technician
        $res = $this->notifyUser($job_card->technitian_id);

        return response()->json([
            'status' => 1,
            'msg' => 'OTP Sent Successfully'
        ]);
    }

    public function engineerServiceRequestUpdate(Request $request){

        $this->validate($request,[
            "technitian_id"=>"required|numeric",
        ]);

        $service_request = ServiceRequest::where('id',$request->request_id)->first();
        $model_id = $service_request->product_id;
        $product_model = ProductModel::where('id',$model_id)->first();

        $technician_id = $request->technitian_id;
        $user_territory = UserTerritory::where('user_id',$technician_id)->first();
        if(!$user_territory){return response()->json(['error'=>"No Territory Defined for this user"],422);}
        $territory = Territory::find($user_territory->territory_id);

        $job_card = new JobCard();
        $job_card->service_type_id = null;
        $job_card->call_type_id = 1;
        $job_card->product_id = $product_model->product_id;
        $job_card->model_id = $product_model->id;
        $job_card->customer_id = $service_request->customer_id;
        $job_card->customer_name = $service_request->customer_name;
        $job_card->customer_moblie = $service_request->customer_mobile;
        $job_card->technitian_id = $request->technitian_id;
        $job_card->area_id = $service_request->area_id;
        $job_card->engineer_id = $service_request->engineer_id;
        $job_card->district_id = $request->district_id;
        $job_card->upazila_id = $request->upazila_id;
        $job_card->territory_id = $territory->id;
        $job_card->chassis_number = $service_request->chassis_number;
        $job_card->lat = $service_request->lat;
        $job_card->lon = $service_request->lon;
        $job_card->job_creator = $service_request->request_creator;
        $job_card->remarks = $service_request->remarks;
        $job_card->service_wanted_at = $service_request->service_requested_at;
        $job_card->service_date = date("Y-m-d",strtotime($service_request->service_requested_at));
        $job_card->created_at = Carbon::now();

        $job_card->save();

        $job_card->job_card_no = 'MS'.$job_card->id;

        if ($job_card->save()) {
            $technician = User::where('id',$request->technitian_id)->first();
            $service_request->is_agree =1;
            $service_request->save();
            //send otp for technician verification
            $unique_code = mt_rand(1000,9999);
            $smscontent = 'সার্ভিস রিকুয়েস্ট জব কার্ড নম্বর '."$unique_code".'. টেকনিশিয়ানকে সার্ভিস শুরু করার পূর্বে প্রদত্ত জব কার্ড নম্বর প্রদান করুন। আপনার টেকনিশিয়ান হচ্ছে '."$technician->name($technician->mobile)";
            $mobileno = $job_card->customer_moblie;

            $respons = $this->sendsms($ip = '192.168.100.213', $userid = 'motors', $password = 'Asdf1234', $smstext = urlencode($smscontent), $receipient = urlencode($mobileno));

            $otp = new Otp();
            $otp->job_card_id = $job_card->id;
            $otp->otp_code = $unique_code;
            $otp->mobile = $mobileno;
            $otp->status = 0;
            $otp->save();

            //send notification to technician
            $res = $this->notifyUser($request->technitian_id);
        }

        $this->saveJobCardDetail($job_card->id,$request->technitian_id);

        return response()->json([
            'status' => 1,
            'msg' => 'Successfully Send Request'
        ]);
    }

    public function engineerJobCardUpdate(Request $request){

        $this->validate($request,[
            "technician_id"=>"required|numeric",
        ]);

        $job_card = JobCard::where('id',$request->job_card_id)->first();
        $job_card->technitian_id = $request->technician_id;

        $job_card->save();

        if ($job_card->save()) {
            $technician = User::where('id',$request->technician_id)->first();
            //send otp for technician verification
            $unique_code = mt_rand(1000,9999);
            $smscontent = 'সার্ভিস রিকুয়েস্ট জব কার্ড নম্বর '."$unique_code".'. টেকনিশিয়ানকে সার্ভিস শুরু করার পূর্বে প্রদত্ত জব কার্ড নম্বর প্রদান করুন। আপনার টেকনিশিয়ান হচ্ছে '."$technician->name($technician->mobile)";
            $mobileno = $job_card->customer_moblie;

            $respons = $this->sendsms($ip = '192.168.100.213', $userid = 'motors', $password = 'Asdf1234', $smstext = urlencode($smscontent), $receipient = urlencode($mobileno));

            $otp = new Otp();
            $otp->job_card_id = $job_card->id;
            $otp->otp_code = $unique_code;
            $otp->mobile = $mobileno;
            $otp->status = 0;
            $otp->save();

            //send notification to technician
            $res = $this->notifyUser($request->technician_id);
        }

        $this->saveJobCardDetail($job_card->id,$request->technician_id);

        return response()->json([
            'status' => 1,
            'msg' => 'Successfully Send Request'
        ]);
    }

    private function saveJobCardDetail($job_card_id,$technitian_id){
        $job_card_details = JobCardDetail::where('job_card_id',$job_card_id)->get();
        foreach($job_card_details as $job_card_detail){
            $job_card_detail->delete();
        }

        $job_card_detail= new JobCardDetail;
        $job_card_detail->job_card_id = $job_card_id;
        $job_card_detail->user_id = $technitian_id;
        $job_card_detail->save();

        return 1;
    }

    public function engineerTechnician(Request $request){

        $user_are = UserArea::where('user_id',$request->engineer_id)->first();

        if ($user_are) {
            $technicians = UserTerritory::select('users.id','users.username','users.name','territories.name as territory_name','supervisors.name as supervisor_name','supervisors.username as supervisor_staffid')
                ->join('users','users.id','user_territories.user_id')
                ->leftjoin('users as supervisors','supervisors.id','user_territories.supervisor_id')
                ->join('territories','territories.id','user_territories.territory_id')
                ->where('territories.area_id',$user_are->area_id)
                ->orderBy('supervisors.id',"ASC")
                ->get();
            return response()->json([
                'status' => 1,
                'technicians' => $technicians
            ]);
        }else {
            return response()->json([
                'status' => 0,
                'technicians' => []
            ]);
        }
    }

    public function notifyUser($technician_id){

        $user = User::where('id', $technician_id)->first();

        if (isset($user->device_token)) {
            $notification_id = $user->device_token;
            $title = "Please check your job list";
            $message = "Have good day!";
            $id = $user->id;
            $type = "basic";

            $res = send_notification_FCM($notification_id, $title, $message, $id,$type);

            if($res == 1){
                return response()->json([
                    'status'=>1
                ]);
            }else{
                return response()->json([
                    'status'=>0
                ]);
            }
        }

    }

    public function sendsms($ip, $userid, $password, $smstext, $receipient) {
        $smsUrl = "http://{$ip}/httpapi/sendsms?userId={$userid}&password={$password}&smsText=" . $smstext . "&commaSeperatedReceiverNumbers=" . $receipient;
        //echo $smsUrl; exit();
        $response = file_get_contents($smsUrl);
        //print_r($response); exit();
        return json_decode($response);
    }

    public function getPendingServiceRequests(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $user_token = UserToken::where("token",$token)->first();
        if(!$user_token){ return response()->json(['error'=>"Unauthorized"],401);}

        $pending_job_list = JobCard::where('engineer_id',$user_token->user_id)->where('job_status',NULL)->get();
        
        $pending_service_request = ServiceRequest::where('engineer_id',$user_token->user_id)
            ->where('is_agree', NULL)
            ->get();

        return response()->json([
            'status'=> 1,
            'pending_job_list' => count($pending_job_list),
            'pending_service_request' => count($pending_service_request)
        ]);
    }

}
