<?php
namespace App\Http\Controllers;

use App\ApprovedChassis;
use App\Exports\JobCardExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use App\JobCard;
use App\Territory;
use App\Area;
use App\User;
use App\Product;
use App\CallType;
use App\ChassisImage;
use App\ServiceType;
use App\Http\Requests\JobCardStoreRequest;
use App\Http\Requests\JobCardUpdateRequest;
use App\JobCardDetail;
use App\UserArea;
use DateTime;

class JobCardController extends Controller{

    public function __construct(){
       $this->middleware('auth');
    }


    public function index(Request $request){

        $is_approved = $request->is_approved ? $request->is_approved : 0;
        $from_date = $request->from_date ? date('Y-m-d',strtotime($request->from_date)) : date('Y-m-01', strtotime(date('Y-m-01').' -1 month'));
        $to_date = $request->to_date ? date('Y-m-d',strtotime($request->to_date)) : date('Y-m-d') ;
        $searchId = $request->search ? $request->search : 0;
        $chassis_number = $request->chassis_number;
        $products = Product::all();
        $product_id = $request->product_id;

        if(Auth::user()->role_id == 1){
            $job_cards = JobCard::query()->with(['area','territory','engineer','technitian','participant','call_type','service_type','product','approver','model']);

            if($searchId){
                $job_cards = $job_cards->where('id',$searchId);
            }
            if($chassis_number){
                $job_cards = $job_cards->where('chassis_number',$chassis_number);
            }
            if($product_id){
                $job_cards = $job_cards->where('product_id',$product_id);
            }

            $job_cards = $job_cards->whereDate('service_date',">=",$from_date)
                ->whereDate('service_date',"<=",$to_date)
                ->where('is_approved',$is_approved)->orderBy('id','Desc')
                ->paginate(50);
        }else{

            $user_id = Auth::user()->id;

            $job_cards = JobCard::query()->with(['area','territory','engineer','technitian','participant','call_type','service_type','product','approver','model']);

            if($searchId){
                $job_cards = $job_cards->where('id',$searchId);
            }elseif ($chassis_number) {
                $job_cards = $job_cards->where('chassis_number', $chassis_number);
            }elseif ($product_id) {
                $job_cards = $job_cards->where('product_id', $product_id);
            }

            $job_cards = $job_cards->where('engineer_id',$user_id)
                ->whereDate('service_date',">=",$from_date)
                ->whereDate('service_date',"<=",$to_date)
                ->where('is_approved',$is_approved)
                ->paginate(50);;
        }
        
        return view("job_card.pending_job_card_list",compact("job_cards","products"));
    }

    public function pendingJobCard(Request $request){

        $from_date = $request->from_date ? date('Y-m-d',strtotime($request->from_date)) : date('Y-m-01', strtotime(date('Y-m-01').' -1 month'));
        $to_date = $request->to_date ? date('Y-m-d',strtotime($request->to_date)) : date('Y-m-d') ;
        $searchId = $request->search ? $request->search : 0;
        $chassis_number = $request->chassis_number;
        $products = Product::all();
        $product_id = $request->product_id;

        if(Auth::user()->role_id == 1){
            $job_cards = JobCard::query()->with(['area','territory','engineer','technitian','participant','call_type','service_type','product','approver','model','image']);

            if($searchId){
                $job_cards = $job_cards->where('id',$searchId);
            }
            if($chassis_number){
                $job_cards = $job_cards->where('chassis_number',$chassis_number);
            }
            if($product_id){
                $job_cards = $job_cards->where('product_id',$product_id);
            }
            $job_cards = $job_cards->whereDate('service_date',">=",$from_date)
                ->whereDate('service_date',"<=",$to_date)
                ->where('is_approved',0)
                //->where('id','263607')
                ->orderBy('id','desc');
        }else{

            $user_id = Auth::user()->id;

            $job_cards = JobCard::query()->with(['area','territory','engineer','technitian','participant','call_type','service_type','product','approver','model','image']);

            if($searchId){
                $job_cards = $job_cards->where('id',$searchId);
            }elseif ($chassis_number) {
                $job_cards = $job_cards->where('chassis_number', $chassis_number);
            }elseif ($product_id) {
                $job_cards = $job_cards->where('product_id', $product_id);
            }

            $job_cards = $job_cards
                ->where('engineer_id',$user_id)
                ->whereDate('service_date',">=",$from_date)
                ->whereDate('service_date',"<=",$to_date)
                ->where('is_approved',0)->orderBy('id','desc');
        }

        $job_cards = $job_cards->paginate(50);

        $job_cards = $this->sameDayChassisCheck($job_cards);
       // dd($job_cards);

        return view("job_card.pending_job_card_list",compact("job_cards","products"));
    }

    public function sameDayChassisCheck($job_cards){
        foreach ($job_cards as $job){
            if (!empty($job->chassis_number)){
                $same_day_chessis_job = $job_cards->filter(function ($item) use ($job){
                    return Carbon::parse($item->created_at)->format('Y-m-d') === Carbon::parse($job->created_at)->format('Y-m-d') && $item->chassis_number === $job->chassis_number;
                });
                $same_month_chessis_job = $job_cards->filter(function ($item) use ($job){
                    return Carbon::parse($item->created_at)->format('Y-m') === Carbon::parse($job->created_at)->format('Y-m') && $item->chassis_number === $job->chassis_number;
                });

                if (count($job->image) > 0){
                    $has_image = 1;
                }else{
                    $has_image = 0;
                }

               if ($same_day_chessis_job->count() > 1){
                   $job->color = '#CB2D2D';
                   $job->flag = 'hide';
               }elseif ($same_month_chessis_job->count() > 1){
                   $job->color = '#FFFF00';
               }elseif ($has_image == 1){
                   $job->color = '#aa95b1';
               }
               else {
                   $job->color = '';
               }
            }
        }
        return $job_cards;
    }

    public function approveJobCard(Request $request){
        $is_approved = 1;
        $from_date = $request->from_date ? date('Y-m-d',strtotime($request->from_date)) : date('Y-m-01', strtotime(date('Y-m-01').' -1 month'));
        $to_date = $request->to_date ? date('Y-m-d',strtotime($request->to_date)) : date('Y-m-d') ;
        $searchId = $request->search ? $request->search : 0;
        $chassis_number = $request->chassis_number;
        $products = Product::all();
        $product_id = $request->product_id;

        if(Auth::user()->role_id == 1){
            $job_cards = JobCard::query()->with(['area','territory','engineer','technitian','participant','call_type','service_type','product','approver','model']);

            if($searchId){
                $job_cards = $job_cards->where('id',$searchId);
            }
            if($chassis_number){
                $job_cards = $job_cards->where('chassis_number',$chassis_number);
            }
            if($product_id){
                $job_cards = $job_cards->where('product_id',$product_id);
            }

            $job_cards = $job_cards->whereDate('service_date',">=",$from_date)
                ->whereDate('service_date',"<=",$to_date)
                ->where('is_approved',$is_approved)->orderBy('id','Desc')
                ->paginate(50);
        }else{

            $user_id = Auth::user()->id;

            $job_cards = JobCard::query()->with(['area','territory','engineer','technitian','participant','call_type','service_type','product','approver','model']);

            if($searchId){
                $job_cards = $job_cards->where('id',$searchId);
            }elseif ($chassis_number) {
                $job_cards = $job_cards->where('chassis_number', $chassis_number);
            }elseif ($product_id) {
                $job_cards = $job_cards->where('product_id', $product_id);
            }

            $job_cards = $job_cards->where('engineer_id',$user_id)
                ->whereDate('service_date',">=",$from_date)
                ->whereDate('service_date',"<=",$to_date)
                ->where('is_approved',$is_approved)
                ->paginate(50);;
        }

        return view("job_card.approve_job_card_list",compact("job_cards","products"));
    }

    public function create(){
        
        $territories=Territory::all();
        $areas=Area::all();
        $users=User::all();
        $products=Product::all();
        $call_types=CallType::all();
        $service_types=ServiceType::all();
        return view("job_card.job_card_create")
             ->with("territories" ,$territories)
             ->with("areas" ,$areas)
             ->with("users" ,$users)
             ->with("products" ,$products)
             ->with("call_types" ,$call_types)
             ->with("service_types" ,$service_types);;
    }

    public function store(JobCardStoreRequest $request){

        $job_card= new JobCard;
        $job_card->territory_id=$request->territory_id;
        $job_card->area_id=$request->area_id;
        $job_card->engineer_id=$request->engineer_id;
        $job_card->technitian_id=$request->technitian_id;
        $job_card->participant_id=$request->participant_id;
        $job_card->product_id=$request->product_id;
        $job_card->call_type_id=$request->call_type_id;
        $job_card->service_type_id=$request->service_type_id;
        $job_card->customer_name=$request->customer_name;
        $job_card->customer_moblie=$request->customer_moblie;
        if($request->buy_date) $job_card->buy_date=date("Y-m-d",strtotime($request->buy_date));
        $job_card->service_wanted_at=date("Y-m-d H:i:s",strtotime($request->service_wanted_at));
        $job_card->service_start_at=date("Y-m-d H:i:s",strtotime($request->service_start_at));
        $job_card->service_end_at=date("Y-m-d H:i:s",strtotime($request->service_end_at));
        $job_card->hour=$request->hour;
        $job_card->service_income=$request->service_income;
        $job_card->is_approved=$request->is_approved;
        $job_card->approver_id=$request->approver_id;
        $job_card->save();

        Session::flash("success", "Created Successfully !");
        return redirect("/job_card");
    }

    public function edit($id){
        
        $territories=Territory::all();
        $areas=Area::all();
        $users=User::all();
        $products=Product::all();
        $call_types=CallType::all();
        $service_types=ServiceType::all();
        $job_card = JobCard::findOrFail($id);
        return view("job_card.job_card_edit",compact("job_card"))
             ->with("territories" ,$territories)
             ->with("areas" ,$areas)
             ->with("users" ,$users)
             ->with("products" ,$products)
             ->with("call_types" ,$call_types)
             ->with("service_types" ,$service_types);;

    }

    public function update(JobCardUpdateRequest $request, $id) {
//dd($request->all());
        $job_card=JobCard::findOrFail($id);
        $job_card->product_id = $request->product_id;
        $job_card->call_type_id = $request->call_type_id;
        $job_card->service_type_id = $request->service_type_id;
        $job_card->customer_name = $request->customer_name;
        $job_card->customer_moblie = $request->customer_moblie;
//        $job_card->buy_date= $request->buy_date ? date("Y-m-d",strtotime($request->buy_date)) : null;
//        $job_card->visited_date = $request->visited_date ? date("Y-m-d",strtotime($request->visited_date)) : null;
//        $job_card->installation_date = $request->installation_date ? date("Y-m-d",strtotime($request->installation_date)) : null;
//        $job_card->service_wanted_at = $request->service_wanted_at ? date("Y-m-d H:i:s",strtotime($request->service_wanted_at)) : null;
//        $job_card->service_start_at = $request->service_start_at ? date("Y-m-d H:i:s",strtotime($request->service_start_at)) : null;
//        $job_card->service_end_at = $request->service_end_at ? date("Y-m-d H:i:s",strtotime($request->service_end_at)) : null;
        $job_card->hour = $request->hour;
        $job_card->service_income = $request->service_income;
        //$job_card->is_approved = $request->is_approved;

        if($request->is_approved == 1){ 
            $job_card->approver_id = Auth::user()->id;
        }else{
            $job_card->approver_id = null;
        }
        $job_card->save();

        Session::flash("success", "Edited Successfully !");
        return redirect("/pending_job_card");
    }

    public function show($id){
        $job_card = JobCard::find($id);
        $chassisImage = ChassisImage::whereNotNull('image_url')->where('job_card_id',$id)->first();
        // dd($chassisImage);
        return view("job_card.job_card_show",compact("job_card",'chassisImage'));
    }

    public function jobCardChassisUpdate(Request $request)
    {
        $job_card = JobCard::find($request->jobcardno);
        $job_card->chassis_number = $request->chassisno;
        $job_card->save();

        $chassis_image = ChassisImage::where('job_card_id',$request->jobcardno)->get()[0];
        $chassis_image->chassis_no = $request->chassisno;
        $chassis_image->is_approved = 1;
        $chassis_image->approved_by = Auth::user()->id;
        $chassis_image->save();

        $checkApprovedChassis = ApprovedChassis::where('chassis_no',$request->chassisno)->first();
        // dd($checkApprovedChassis);
        if(!isset($checkApprovedChassis)){
                $approved_chassis = new ApprovedChassis();
                $approved_chassis->job_card_id = $request->jobcardno;
                $approved_chassis->chassis_no = $job_card->chassis_number;
                $approved_chassis->customer_name = $job_card->customer_name;
                $approved_chassis->customer_mobile = $job_card->customer_moblie;
                $approved_chassis->product_id = $job_card->product_id;
                $approved_chassis->product_model_id = $job_card->model_id;
                $approved_chassis->save();
        }

        return response()->json("success");    
    }

    public function destroy($id){
        $job_card = JobCard::findOrFail($id);
        $job_card_details = JobCardDetail::where('job_card_id',$job_card->id)->get();
        foreach($job_card_details as $job_card_detail){
            $job_card_detail->delete(); 
        }

        $job_card ->delete();
        Session::flash("success", "Deleted Successfully !");
        return redirect("/job_card");
    }

    public function approve(Request $request){
        $job_card = JobCard::findOrFail($request->job_card_id);

        $datetime1 = new DateTime($job_card->service_wanted_at);
        $datetime2 = new DateTime($job_card->service_start_at);
        $interval = $datetime1->diff($datetime2);
        $interval = $interval->format('%H:%I:%S');
        $interval = round($interval);

        $job_card ->is_approved= 1;
        $job_card ->approve_remarks= $request->remark;
        $job_card->approver_id = Auth::user()->id;
        $job_card->is_six_hour = $interval;
        $job_card->save();


        Session::flash("success", "Approved Successfully !");
        return redirect()->back();
    }

    public function jobCardExport(Request $request){
        $is_approved = $request->approve_status;
        $product_id = $request->product_id;
        $chassis_number = $request->chassis_number;
        $from_date = date('Y-m-d',strtotime($request->f_date));
        $to_date = date('Y-m-d',strtotime($request->t_date));

        $job_cards = JobCard::orderBy('id','Desc')
            ->with(['area','territory','engineer','technitian','participant','call_type','service_type','product','approver','model','district','upazila','section'])
            ->whereBetween('service_date',[$from_date,$to_date])
            ->where('is_approved',$is_approved);
        if (Auth::user()->role_id != 1){
            $user_id = Auth::user()->id;
            $job_cards = $job_cards->where('engineer_id',$user_id);
        }
        if ($product_id){
            $job_cards = $job_cards->where('product_id',$product_id);
        }
        if ($chassis_number){
            $job_cards = $job_cards->where('chassis_number',$chassis_number);
        }
        $job_cards = $job_cards->get();

        $result = [];

        foreach ($job_cards as $job_card){

            if($job_card->service_wanted_at && $job_card->service_start_at){
                $datetime1 = new DateTime($job_card->service_wanted_at);
                $datetime2 = new DateTime($job_card->service_start_at);
                $interval_date = $datetime1->diff($datetime2);
                $interval = $interval_date->format('%h')." Hours ".$interval_date->format('%i')." Minutes";
            }else {
                $interval = 0;
            }

            $result[] = [
                'id' =>$job_card->id,
                'section' =>isset($job_card->section) ? $job_card->section->name : '',
                'territory' =>isset($job_card->territory) ? $job_card->territory->name : '',
                'district'=>isset($job_card->district) ? $job_card->district->name : '',
                'upazila'=>isset($job_card->upazila) ? $job_card->upazila->name:'',
                'area' =>isset($job_card->area) ? $job_card->area->name : '',
                'engineer' =>isset($job_card->engineer) ? $job_card->engineer->name : '',
                'technician' =>isset($job_card->technitian) ? $job_card->technitian->name : '',
                'technitian_username' =>isset($job_card->technitian) ? $job_card->technitian->username : '',
                'creator_name' =>$job_card->job_creator,
                'product' =>isset($job_card->product) ? $job_card->product->name : '',
                'model_name' =>isset($job_card->model) ? $job_card->model->model_name:'',
                'call_type' =>isset($job_card->call_type) ? $job_card->call_type->name : '',
                'service_type' =>isset($job_card->service_type) ? $job_card->service_type->name : '',
                'customer_name' =>$job_card->customer_name,
                'customer_moblie' =>$job_card->customer_moblie,
                'buy_date' => date('Y-m-d',strtotime($job_card->buy_date)),
                'visited_date' => date('Y-m-d',strtotime($job_card->visited_date)),
                'installation_date' => date('Y-m-d',strtotime($job_card->installation_date)),
                'service_wanted_at' => date('Y-m-d H:i:s',strtotime($job_card->service_wanted_at)),
                'service_start_at' => date('Y-m-d H:i:s',strtotime($job_card->service_start_at)),
                'service_end_at' => date('Y-m-d H:i:s',strtotime($job_card->service_end_at)),
                'hour' =>$job_card->hour,
                // 'service_income' =>$job_card->service_income,
                'is_approved' =>$job_card->is_approved,
                'rating' => $job_card->rating,
                'job_status' => $job_card->job_status,
                'chassis_number' => $job_card->chassis_number,
                'running_houre' => $job_card->running_houre,
                'spare_parts_sale' => $job_card->spare_parts_sale,
                'created_at' => date('Y-m-d H:i:s',strtotime($job_card->created_at)),
                'time_app' => $job_card->time_app,
                'service_date'=>  date('Y-m-d',strtotime($job_card->service_date)),
                'is_six'=> $interval,
                'total_service_cost'=> $job_card->total_service_cost,
                'discount_amount'=> $job_card->discount_amount,
                'total_receviable'=> $job_card->total_receviable,
                'service_income'=> $job_card->service_income,
                'participant'=> isset($job_card->participant) ? $job_card->participant->name: '',
                'approve_remarks'=> $job_card->approve_remarks
            ];
        }
        $this->exportexcel($result, 'Job_Card');
        return redirect()->back();
        //return Excel::download(new JobCardExport($request), 'JobCard.xlsx');
    }

    public function exportexcel($result,$filename){
        for($i=0; $i<count($result); $i++){
            unset($result[$i]['PageNo']);
        }

        $arrayheading[0] = array_keys($result[0]);
        $result = array_merge($arrayheading, $result);

        header("Content-Disposition: attachment; filename=\"{$filename}.xls\"");
        header("Content-Type: application/vnd.ms-excel;");
        header("Pragma: no-cache");
        header("Expires: 0");
        $out = fopen("php://output", 'w');
        fputs( $out, "\xEF\xBB\xBF" ); // UTF-8 BOM !!!!!

        foreach ($result as $data)
        {
            fputcsv($out, $data);
        }

        fclose($out);
        exit();
    }

}
