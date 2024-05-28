<?php

namespace App\Http\Controllers;

use App\Area;
use App\CallType;
use App\Customer;
use App\District;
use App\JobCard;
use App\JobCardDetail;
use App\Otp;
use App\Product;
use App\Section;
use App\ServiceRequest;
use App\ServiceType;
use App\Territory;
use App\Topic;
use App\Upazila;
use App\User;
use App\UserTerritory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as JobCardResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ServiceRequestController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){

        if($user = Auth::user()->role_id == 2){
            if(Auth::user()->user_area){
                $areas = Area::where('id',Auth::user()->user_area->area_id)->get();
                if ($request->area_id) {
                    $service_requests = ServiceRequest::orderBy('id','Desc')
                        ->where('area_id',$request->area_id)
                        ->where('is_agree',null)
                        ->with('customer','technician')
                        ->paginate(20);
                }else{
                    $service_requests = ServiceRequest::orderBy('id','Desc')->with('customer','technician')->where('is_agree',null)->paginate(20);
                }
            }else{
                echo "area not defined";
            }
        }else{
            $areas=Area::all();
            if ($request->area_id) {
                $service_requests = ServiceRequest::orderBy('id','Desc')->where('area_id',$request->area_id)->where('is_agree',null)->with('customer','technician')->paginate(20);
            }else{
                $service_requests = ServiceRequest::orderBy('id','Desc')->with('customer','technician')->where('is_agree',null)->paginate(20);
            }
        }
        return view("service_request.list",compact(["service_requests","areas"]));
    }

    public function create(){
        $call_type = CallType::all();
        $technitians = User::where('role_id',3)->get();
        return view("service_request.create",compact('call_type','technitians'));
    }

    public function store(Request $request){

        $this->validate($request,[
            "customer_name"=>"required",
            "customer_mobile"=>"required",
            "call_type_id"=>"required",
            "technitian_id"=>"required",
            "service_wanted_at"=>"required",
        ]);

        $job_card = new JobCard();
        $job_card->service_type_id = null;
        $job_card->call_type_id = 1;
        $job_card->customer_name = $request->customer_name;
        $job_card->customer_moblie = $request->customer_mobile;
        $job_card->technitian_id = $request->technitian_id;
        $job_card->engineer_id = Auth::user()->id;
        $job_card->job_creator = 'engineer';
        $job_card->service_wanted_at = date("Y-m-d H:i:s",strtotime($request->service_wanted_at));
        $job_card->service_date = date("Y-m-d",strtotime($request->service_wanted_at));
        $job_card->created_at = Carbon::now();

        $job_card->save();
        $job_card->job_card_no = 'MS'.$job_card->id;
        $job_card->save();

        if ($job_card->save()) {

            //send otp for technician verification
            $unique_code = mt_rand(1000,9999);
            $smscontent = 'সার্ভিস রিকুয়েস্ট ওটিপি '."$unique_code".'. টেকনিশিয়ানকে সার্ভিস শুরু করার পূর্বে প্রদত্ত ওটিপি প্রদান করুন।';
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

        Session::flash("success", "Created Successfully !");
        return redirect()->route('service_request.index');
    }

    public function edit($id){
        $service_request = ServiceRequest::findOrFail($id);
        $sections = Section::all();
        $topics = Topic::all();
        $products = Product::all();
        $districts = District::all();
        $upazilas = Upazila::all();
        $service_types = ServiceType::all();
        $areas = Area::all();
        $customers = Customer::all();
        $users = User::where('role_id',2)->get();
        //$service_warranty_types = ServiceWarrantyType::all();
        $technitians = User::where('role_id',3)->get();
        $call_type = CallType::all();

        return view("service_request.edit",compact("service_request"))
            ->with("districts" ,$districts)
            ->with("upazilas" ,$upazilas)
            ->with("service_types" ,$service_types)
            ->with("areas" ,$areas)
            ->with("sections" ,$sections)
            ->with("topics" ,$topics)
            ->with("technitians" ,$technitians)
            ->with("customers" ,$customers)
            ->with("call_type" ,$call_type)
            ->with("products" ,$products)
            ->with("users" ,$users);
    }

    public function update(Request $request, $id) {

        $this->validate($request,[
            "district_id"=>"required|numeric|exists:districts,id",
            "upazila_id"=>"required|numeric|exists:upazilas,id",
            "area_id"=>"required|numeric|exists:areas,id",
            "remarks"=>"nullable|max:200",
            "technitian_id"=>"required|numeric",
        ]);

        $service_request = ServiceRequest::findOrFail($id);

        if ($service_request && $request->technitian_id) {

            $technitian_id = $request->technitian_id;

            $user_territory = UserTerritory::where('user_id',$technitian_id)->first();
            if(!$user_territory){return response()->json(['error'=>"No Territory Defined for this user"],422);}
            $territory = Territory::find($user_territory->territory_id);

            $job_card = new JobCard;
            $job_card->job_card_no = time();
            $job_card->area_id = $request->area_id;
            $job_card->district_id = $request->district_id;
            $job_card->upazila_id = $request->upazila_id;
            $job_card->territory_id = $territory->id;
            $job_card->technitian_id = $technitian_id;
            $job_card->time_app = '';
            $job_card->engineer_id = $service_request->engineer_id;
            $job_card->customer_id = $service_request->customer_id;
            $job_card->participant_id = $request->participant_id;
            $job_card->product_id = $request->product_id;
            $job_card->call_type_id = $request->call_type_id;
            $job_card->customer_name = $request->customer_name;
            $job_card->customer_moblie = $request->customer_mobile;
            $job_card->address = $service_request->address;
            $job_card->chassis_number = $service_request->chassis_number;

            $job_card->is_approved = 0;
            $job_card->approver_id = null;
            $job_card->remarks = $request->remarks;
            $job_card->request_id = $service_request->request_id;
            $job_card->service_wanted_at = $service_request->created_at;
            $job_card->job_creator = $service_request->request_creator;
            $job_card->technician_asign_at = Carbon::now();
            $job_card->service_date = date("Y-m-d",strtotime($service_request->created_at));

            $job_card->lat = $service_request->lat;
            $job_card->lon = $service_request->lon;

            $job_card->save();
            $job_card->job_card_no = 'MS'.$job_card->id;

            if ($job_card->save()) {
                $service_request->is_agree = 1;
                $service_request->save();

                //send otp for technician verification
                $unique_code = mt_rand(1000,9999);
                $smscontent = 'সার্ভিস রিকুয়েস্ট ওটিপি '." $unique_code ".'. টেকনিশিয়ানকে সার্ভিস শুরু করার পূর্বে প্রদত্ত ওটিপি প্রদান করুন।';
                $mobileno = $service_request->customer_mobile;

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

            $this->saveJobCardDetail($job_card->id,$technitian_id,$request->customer_id);
            Session::flash("success", "Updated Successfully !");
            return redirect()->route('job_card.index');
        }

    }

    public function notifyUser($technician_id){

        $user = User::where('id', $technician_id)->first();

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

    public function sendsms($ip, $userid, $password, $smstext, $receipient) {
        $smsUrl = "http://{$ip}/httpapi/sendsms?userId={$userid}&password={$password}&smsText=" . $smstext . "&commaSeperatedReceiverNumbers=" . $receipient;
        //echo $smsUrl; exit();
        $response = file_get_contents($smsUrl);
        //print_r($response); exit();
        return json_decode($response);
    }

    private function saveJobCardDetail($job_card_id,$technitian_id){

        $job_card_details = JobCardDetail::where('job_card_id',$job_card_id)->get();
        foreach($job_card_details as $job_card_detail){
            $job_card_detail->delete();
        }

        $job_card_detail= new JobCardDetail;
        $job_card_detail->job_card_id=$job_card_id;
        $job_card_detail->user_id = $technitian_id;
        $job_card_detail->save();

        return 1;
    }

    public function show($id){
        $service_request = ServiceRequest::find($id);
        return view("service_request.show",compact("service_request"));
    }

    public function destroy($id){
        $service_request = ServiceRequest::find($id);
        $service_request ->delete();
        Session::flash("success", "Deleted Successfully !");
        return response()->json([
            'status'=>200,
            'success'=>'Data Deleted Successfully'
        ]);
    }

}
