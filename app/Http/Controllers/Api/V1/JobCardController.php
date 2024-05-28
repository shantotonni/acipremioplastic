<?php
namespace App\Http\Controllers\Api\V1;

use App\ChassisImage;
use App\Http\Controllers\Controller;
use App\Otp;
use App\Product;
use App\ServiceIncomeAmount;
use App\ServiceIncomeDetails;
use App\ServiceType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\JobCard;
use App\Territory;
use App\Http\Requests\JobCardStoreRequest;
use App\Http\Requests\JobCardUpdateRequest;
use App\UserToken;
use App\JobCardDetail;
use App\Http\Resources\JobCard as JobCardResource;
use App\UserArea;
use App\UserTerritory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class JobCardController extends Controller{

    public function __construct(){
      // $this->middleware('auth');
    }

    public function index(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $user_token = UserToken::where("token",$token)->first();
        if(!$user_token){ return response()->json(['error'=>"Unauthorized"],401);}
        $job_cards = JobCard::where('technitian_id',$user_token->user_id)
                   ->whereMonth('service_date',date('m'))
                   ->whereYear('service_date',date('Y'))
                   ->orderBy('id', 'asc')
                   ->get();
        return JobCardResource::collection($job_cards);
    }

    public function filterJobCard(Request $request){

        $token = str_replace("token ","",$request->header('Authorization'));
        $user_token = UserToken::where("token",$token)->first();
        if(!$user_token){ return response()->json(['error'=>"Unauthorized"],401);}

        $from_date = date('Y-m-d',strtotime($request->from_date));
        $to_date = date('Y-m-d',strtotime($request->to_date));

        $job_cards = JobCard::where('technitian_id',$user_token->user_id);

        if ($request->has('from_date') && $request->has('to_date')){
            $job_cards = $job_cards->whereBetween('job_cards.service_date',[$from_date,$to_date]);
        }else{
            $job_cards = $job_cards->whereMonth('service_date',date('m'))->whereYear('service_date',date('Y'));
        }
        if ($request->has('job_status')){
            $job_cards = $job_cards->where('job_cards.job_status',$request->job_status);
        }
        if ($request->has('is_due')){
            $job_cards = $job_cards->where('job_cards.is_due',$request->is_due);
        }

        $job_cards = $job_cards->orderBy('job_cards.id', 'asc')->get();

        return JobCardResource::collection($job_cards);
    }

    public function job_card_by_date(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $user_token = UserToken::where("token",$token)->first();
        if(!$user_token){ return response()->json(['error'=>"Unauthorized"],401);}

        $from_date = date('Y-m-d',strtotime($request->from_date));
        $to_date = date('Y-m-d',strtotime($request->to_date));

        $job_cards = JobCard::leftjoin('job_card_details','job_card_details.job_card_id','job_cards.id')
                    ->where('job_card_details.user_id',$user_token->user_id)
                    ->whereBetween('job_cards.service_date',[$from_date,$to_date])
                    ->orderBy('job_cards.id', 'asc')
                    ->get();
        return JobCardResource::collection($job_cards);
    }

    public function store(JobCardStoreRequest $request){

        if(empty($request->chassis_number)){
            return response()->json([
                'status' => 0,
                'msg' => "Chassis number can't be empty!"
            ]);
        }

        Log::info("Service Income =".$request->service_income);
        $token = str_replace("token ","",$request->header('Authorization'));
        $user_token = UserToken::where("token",$token)->first();
        $technitian_id = $user_token->user_id;

        $user_territory = UserTerritory::where('user_id',$technitian_id)->first();
        if(!$user_territory){return response()->json(['error'=>"No Territory Defined for this user"],422);}
        $territory = Territory::find($user_territory->territory_id);

        $area_id = $territory->area_id;
        $user_area = UserArea::where('area_id',$area_id)->first();
        if(!$user_area){return response()->json(['error'=>"Engineer undefined"],422);}
        $engineer_id = $user_area->user_id;

        if($this->isAlreadyExist($technitian_id,$request->time_app)) {
            return response()->json(['error'=>"Duplicate entry"],409);
        }

        $product = Product::where('id',$request->product_id)->first();
        if ($product){
            $type = $product->name;
        }else{
            $type = '';
        }

        $job_card = new JobCard;
        $job_card->job_card_no = time();
        $job_card->territory_id = $territory->id;
        $job_card->area_id = $area_id;
        $job_card->engineer_id = $engineer_id;
        $job_card->technitian_id = $technitian_id;
        $job_card->participant_id = $request->participant_id;
        $job_card->product_id = $request->product_id;
        $job_card->model_id = $request->model_id;
        $job_card->call_type_id = $request->call_type_id;
        $job_card->service_type_id = $request->service_type_id;
        $job_card->customer_name = $request->customer_name;
        $job_card->customer_moblie = $request->customer_moblie;
        $job_card->chassis_number = $request->chassis_number;
        $job_card->running_houre = $request->running_houre;
        $job_card->section_id = $request->section_id;
        $job_card->product_type = $type;

        $job_card->job_creator = 'technician';
        $job_card->job_status = 'started';
        $job_card->service_date = date("Y-m-d",strtotime(Carbon::now()));
        if($request->buy_date != null) $job_card->buy_date=date("Y-m-d",strtotime($request->buy_date));

        if($request->visited_date != null) {
            $job_card->visited_date = date("Y-m-d",strtotime($request->visited_date));
            $job_card->service_date = date("Y-m-d",strtotime($request->visited_date));
        }

        if($request->installation_date != null){
             $job_card->installation_date = date("Y-m-d",strtotime($request->installation_date));
             $job_card->service_date = date("Y-m-d",strtotime($request->installation_date));
        }

        if($request->service_wanted_at != null) $job_card->service_wanted_at = date("Y-m-d H:i:s",strtotime($request->service_wanted_at));
        if($request->service_start_at != null)  $job_card->service_start_at = date("Y-m-d H:i:s",strtotime($request->service_start_at));

        if($request->service_end_at != null) {
             $job_card->service_end_at=date("Y-m-d H:i:s",strtotime($request->service_end_at));
             $job_card->service_date = date("Y-m-d",strtotime($request->service_end_at));
        }

        if($request->hour != null) $job_card->hour=$request->hour;
        if($request->service_income != null) $job_card->service_income=$request->service_income;

        $job_card->is_approved = 0;
        $job_card->approver_id = null;
        $job_card->rating = $request->rating;
        $job_card->time_app = $request->time_app;
        $job_card->save();
        $job_card->job_card_no = 'MS'.$job_card->id;

        if ($job_card->save()) {
            $this->saveJobCardDetail($job_card->id, $technitian_id);

            $chassisImage = new ChassisImage();

            if (!empty($request->chassis_image)) {
                $image = $request->chassis_image;  // your base64 encoded
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = Str::random(10) .'_'.date('Y-m-d').'_'. '.jpg';

                Image::make($image)->resize(500,500,function ($constraint){
                    $constraint->aspectRatio();
                })->save(public_path('chassis_images/').$imageName);

                $chassisImage->job_card_id = $job_card->id;
                $chassisImage->image_url = $imageName;
                $chassisImage->chassis_no = $request->chassis_number;
                $chassisImage->is_approved = 0;
                $chassisImage->save();
            }
    
            // end my code
            return new JobCardResource($job_card);
        }
    }

    private function isAlreadyExist($technitian_id,$time_app){
         $job_card = JobCard::where('technitian_id',$technitian_id)->where('time_app',$time_app)->first();
         return $job_card ? 1 : 0;
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

    public function update(JobCardUpdateRequest $request, $id) {

        $territory = Territory::find($request->territory_id);
        $area = $territory->area_id;
        $engineer = $territory->area->user_area->user_id;
        if(!$engineer){return response()->json(['error'=>"Engineer undefined"],422);}

        $job_card = JobCard::findOrFail($id);
        $job_card->territory_id = $request->territory_id;
        $job_card->area_id = $request->area_id;
        $job_card->engineer_id = $request->engineer_id;
        $job_card->technitian_id = $request->technitian_id;
        $job_card->participant_id = $request->participant_id;
        $job_card->product_id = $request->product_id;
        $job_card->model_id = $request->model_id;
        $job_card->call_type_id = $request->call_type_id;
        $job_card->service_type_id  =$request->service_type_id;
        $job_card->customer_name = $request->customer_name;
        $job_card->customer_moblie = $request->customer_moblie;
        if($request->buy_date) $job_card->buy_date = date("Y-m-d",strtotime($request->buy_date));
        $job_card->service_wanted_at= date("Y-m-d H:i:s",strtotime($request->service_wanted_at));
        $job_card->service_start_at = date("Y-m-d H:i:s",strtotime($request->service_start_at));
        $job_card->service_end_at = date("Y-m-d H:i:s",strtotime($request->service_end_at));
        $job_card->hour = $request->hour;
        $job_card->service_income = $request->service_income;
        $job_card->is_approved = 0;
        $job_card->approver_id = null;
        $job_card->rating = $request->rating;
        $job_card->save();

        return  new JobCardResource($job_card);
    }

    public function serviceIncomeUpdate(Request $request){
        return $request->all();
    }

    public function show($id){
        return new JobCardResource(JobCard::find($id));
    }

    public function destroy($id){
        $job_card = JobCard::findOrFail($id);
        $job_card ->delete();
        return response()->json(['success'=>'Deleted Successfully'],200);
    }

    public function jobCardDelete(Request $request){
        $job_card_id = $request->job_card_id;
        $job_card = JobCard::where('id',$job_card_id)->first();
        if ($job_card){
            JobCardDetail::where('job_card_id',$job_card->id)->delete();
            $job_card->delete();
            return response()->json([
                'status'=> 1,
                'message' => 'Deleted Successfully',
            ]);
        }
        return response()->json([
            'status'=> 0,
            'message' => 'Data Not Found',
        ]);
    }

    public function technicianJobList(Request $request){
       $job_list = JobCard::where('technitian_id',$request->technician_id)->orderBy('created_at','desc')->get();
       return response()->json([
           'status'=> 1,
           'job_card' => $job_list,
       ]);
    }

    public function totalJobCard(Request $request){
        $pending_job_list = JobCard::where('technitian_id',$request->technician_id)
            ->where('job_status',NULL)
            ->whereMonth('service_date',date('m'))
            ->whereYear('service_date',date('Y'))
            ->orderBy('created_at','desc')
            ->get();
        return response()->json([
            'status'=> 1,
            'pending_job_list' => count($pending_job_list),
        ]);
    }

    public function technicianVerify(Request $request){

        if (empty($request->chassis_number)) {
            return response()->json([
                'status' => 0,
                'msg' => 'Chasses number Can not be Empty'
            ]);
        }

        $otp = Otp::where('otp_code',$request->otp_code)->where('job_card_id',$request->job_card_id)->where('status',0)->orderBy('id','desc')->first();

        if ($otp) {
            $job_card = JobCard::where('id',$request->job_card_id)->first();
            $job_card->service_start_at = Carbon::now();
            $job_card->job_status = 'started';
            $job_card->chassis_number = $request->chassis_number;
            $job_card->save();

            $otp->status = 1;
            $otp->save();

            return response()->json([
                'status' => 1,
                'msg'=>'Otp Successfully Match'
            ]);
        }else{
            return response()->json([
               'status' => 0,
               'msg' => 'Otp not match'
            ]);
        }
    }
    
    public function technicianVerifyOtpToStartWork(Request $request){

        if (empty($request->otp_code)) {
            return response()->json([
                'status' => 0,
                'msg' => 'OTP Can not be Empty'
            ]);
        }

        $otp = Otp::where('otp_code',$request->otp_code)->where('job_card_id',$request->job_card_id)->where('status',0)->orderBy('id','desc')->first();

        if ($otp) {
            $job_card = JobCard::where('id',$request->job_card_id)->first();
            $job_card->service_start_at = Carbon::now();
            $job_card->job_status = 'started';
            $job_card->save();

            $otp->status = 1;
            $otp->save();

            return response()->json([
                'status' => 1,
                'msg'=>'Otp Successfully Match'
            ]);
        }else{
            return response()->json([
               'status' => 0,
               'msg' => 'Otp not match'
            ]);
        }
    }

    public function sendOtpForfinishJob(Request $request){
        $job_card = JobCard::where('id',$request->job_card_id)->first();

        //send otp for technician verification
        $unique_code = mt_rand(1000,9999);
        $smscontent = 'সার্ভিস সম্পন্ন জব কার্ড নম্বর '."$unique_code".' . টেকনিশিয়ানকে সার্ভিস সম্পন্ন হলে প্রদত্ত জব কার্ড নম্বর প্রদান করুন।';
        $mobileno = $job_card->customer_moblie;

        $respons = $this->sendsms($ip = '192.168.100.213', $userid = 'motors', $password = 'Asdf1234', $smstext = urlencode($smscontent), $receipient = urlencode($mobileno));

        $otp = new Otp();
        $otp->job_card_id = $request->job_card_id;
        $otp->otp_code = $unique_code;
        $otp->mobile = $job_card->customer_moblie;
        $otp->status = 0;
        if ($otp->save()) {
            return response()->json([
                'status' => 1,
                'msg'=>'Otp Successfully Send'
            ]);
        }
    }

    public function sendOtpForfinishJobVerify(Request $request){

        $otp = Otp::where('otp_code',$request->otp_code)->where('status',0)->where('job_card_id',$request->job_card_id)->orderBy('created_at','desc')->first();

        $user_territory = UserTerritory::where('user_id',$request->technitian_id)->first();
        if(!$user_territory){return response()->json(['error'=>"No Territory Defined for this user"],422);}
        $territory = Territory::find($user_territory->territory_id);

        $area_id = $territory->area_id;
        $user_area = UserArea::where('area_id',$area_id)->first();
        if(!$user_area){return response()->json(['error'=>"Engineer undefined"],422);}
        $engineer_id = $user_area->user_id;

        if ($otp) {

            $product = Product::where('id',$request->product_id)->first();
            if ($product){
                $type = $product->name;
            }else{
                $type = '';
            }

            $job_card = JobCard::where('id',$request->job_card_id)->first();
            //$job_card->area_id = $area_id;
            //$job_card->territory_id = $territory->id;
            $job_card->participant_id = $request->participant_id;
            $job_card->section_id = $request->section_id;
            $job_card->product_id = $request->product_id;
            $job_card->model_id = $request->model_id;
            $job_card->service_type_id = $request->service_type_id;
            $job_card->district_id = $request->district_id;
            $job_card->upazila_id = $request->upazila_id;
            $job_card->buy_date = date("Y-m-d",strtotime($request->buy_date));
            $job_card->visited_date = date("Y-m-d",strtotime($request->visited_date));
            $job_card->installation_date = date("Y-m-d",strtotime($request->installation_date));
            $job_card->time_app = $request->time_app;
            $job_card->hour = $request->hour;
            $job_card->service_income = isset($request->service_income) ? $request->service_income : 0;
            $job_card->rating = $request->rating;
            $job_card->running_houre = $request->running_houre;
            $job_card->address = $request->address;
            $job_card->spare_parts_sale = $request->spare_parts_sale;
            $job_card->invoice_number = $request->invoice_number;
            $job_card->service_end_at = Carbon::now();
            $job_card->job_status = 'finished';
            $job_card->product_type = $type;
            $job_card->save();

            $otp->status = 1;
            $otp->save();

            return response()->json([
                'status' => 1,
                'msg'=>'Otp Successfully Match'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => 'Otp not match'
            ]);
        }
    }

    public function sendOtpForfinishJobVerifyNew(Request $request){

        if(empty($request->chassis_number)){
            return response()->json([
                'status' => 0,
                'msg' => "Chassis number can't be empty!"
            ]);
        }
        
        $content = trim(file_get_contents("php://input"));
        $data = json_decode($content,true);
        if (isset($data['service_income'])){
            $total_service_cost = $data['service_income']['total_service_cost'];
            $discount_amount = $data['service_income']['discount_amount'];
            $total_receviable = $data['service_income']['total_receviable'];
            $received_amount = $data['service_income']['received_amount'];
            $due_amount = $data['service_income']['due_amount'];
        }else{
            $total_service_cost = 0;
            $discount_amount = 0;
            $total_receviable = 0;
        }

        $otp = Otp::where('otp_code',$request->otp_code)->where('status',0)->where('job_card_id',$request->job_card_id)->orderBy('created_at','desc')->first();

        $user_territory = UserTerritory::where('user_id',$request->technitian_id)->first();
        if(!$user_territory){return response()->json(['error'=>"No Territory Defined for this user"],422);}
        $territory = Territory::find($user_territory->territory_id);

        $area_id = $territory->area_id;
        $user_area = UserArea::where('area_id',$area_id)->first();
        if(!$user_area){return response()->json(['error'=>"Engineer undefined"],422);}
        $engineer_id = $user_area->user_id;

        if ($otp) {

            $product = Product::where('id',$request->product_id)->first();
            if ($product){
                $type = $product->name;
            }else{
                $type = '';
            }

            $job_card = JobCard::where('id',$request->job_card_id)->first();
            $job_card->participant_id = $request->participant_id;
            $job_card->section_id = $request->section_id;
            $job_card->district_id = $request->district_id;
            $job_card->upazila_id = $request->upazila_id;

            $job_card->product_id = $request->product_id;
            $job_card->model_id = $request->model_id;
            $job_card->service_type_id = $request->service_type_id;

            if($request->buy_date != null) $job_card->buy_date = date("Y-m-d",strtotime($request->buy_date));
            if($request->visited_date != null) $job_card->visited_date = date("Y-m-d",strtotime($request->visited_date));
            if($request->installation_date != null) $job_card->installation_date = date("Y-m-d",strtotime($request->installation_date));
            if($request->service_start_at != null) $job_card->service_start_at = date("Y-m-d H:i:s",strtotime($request->service_start_at));

            $job_card->time_app = $request->time_app;
            $job_card->hour = $request->hour;
            $job_card->rating = $request->rating;
            $job_card->address = $request->address;
            $job_card->spare_parts_sale = $request->spare_parts_sale;
            $job_card->invoice_number = $request->invoice_number;
            $job_card->service_end_at = Carbon::now();
            $job_card->job_status = 'finished';
            $job_card->product_type = $type;

            $job_card->chassis_number = $request->chassis_number;
            $job_card->customer_moblie = $request->customer_moblie;
            $job_card->customer_name = $request->customer_name;
            $job_card->is_due = $request->is_due;

            $job_card->total_service_cost = $total_service_cost;
            $job_card->discount_amount = $discount_amount;
            $job_card->total_receviable = $total_receviable;
            $job_card->save();

            $otp->status = 1;
            $otp->save();

            $this->saveServiceIncomeDetail($job_card->id, $data);

            if($job_card->save()){
                // my code

                    if (!empty($request->chassis_image)) {
                        $chassisImage = ChassisImage::where('job_card_id',$job_card->id)->first();
                        if ($chassisImage){
                            $file_old = public_path('chassis_images').'/'.$chassisImage->image_url;
                            if (file_exists($file_old)){
                                unlink($file_old);
                            }
                        }else{
                            $chassisImage = new ChassisImage();
                        }

                        $image = $request->chassis_image;
                        $image = str_replace('data:image/png;base64,', '', $image);
                        $image = str_replace(' ', '+', $image);
                        $imageName = date('Y-m-d').Str::random(10) . '.jpg';

                        Image::make($image)->resize(500,500,function ($constraint){
                            $constraint->aspectRatio();
                        })->save(public_path('chassis_images/').$imageName);
                
                        $chassisImage->job_card_id = $job_card->id;
                        $chassisImage->image_url = $imageName;
                        $chassisImage->chassis_no = $request->chassis_number;
                        $chassisImage->is_approved = 0;
                        $chassisImage->save();

                    }
            }

            $service_type = ServiceType::where('id',$request->service_type_id)->first();
            if ($service_type->code == 'paidservice'){
                $smscontent = 'প্রিয় গ্রাহক, আপনার প্রদত্ত সার্ভিস চার্জ '."$received_amount".' টাকা আমরা সফলভাবে বুঝে পেয়েছি, বাকি রয়েছে, '."$due_amount".' টাকা। ধন্যবাদ এসিআই মটরসের সাথে থাকার জন্য।';
                $mobileno = $job_card->customer_moblie;
                $respons = $this->sendsms($ip = '192.168.100.213', $userid = 'motors', $password = 'Asdf1234', $smstext = urlencode($smscontent), $receipient = urlencode($mobileno));
            }

            return response()->json([
                'status' => 1,
                'msg'=>'Otp Successfully Match'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'msg' => 'Otp not match'
            ]);
        }
    }

    public function saveServiceIncomeDetail($jobId, $data){
        if (isset($data['service_income'])){
            $service_income_details = $data['service_income']['service_income_details'];
            $received_amount = $data['service_income']['received_amount'];
            $due_amount = $data['service_income']['due_amount'];
            $mrr_number = $data['service_income']['mrr_number'];

            foreach ($service_income_details as $value){
                $service_details = new ServiceIncomeDetails();
                $service_details->job_card_id = $jobId;
                $service_details->service_category_id = $value['service_category_id'];
                $service_details->service_amount = $value['service_amount'];
                $service_details->save();
            }

            $service_amount = new ServiceIncomeAmount();
            $service_amount->job_card_id = $jobId;
            $service_amount->mrr_number = $mrr_number;
            $service_amount->received_amount = $received_amount;
            $service_amount->due_amount = $due_amount;
            $service_amount->save();
            return 1;
        }else{
            return 1;
        }
    }

    public function serviceIncomePartialPayment(Request $request){
        $service_income = new ServiceIncomeAmount();
        $service_income->job_card_id = $request->job_card_id;
        $service_income->mrr_number = $request->mrr_number;
        $service_income->received_amount = $request->received_amount;
        $service_income->due_amount = 0;
        if ($service_income->save()){
            $job_Card = JobCard::where('id',$request->job_card_id)->first();
            $job_Card->is_due = 'N';
            $job_Card->save();

            return response()->json([
                'status' => 200,
                'msg' => 'Partial Payment inserted successfully'
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

}
