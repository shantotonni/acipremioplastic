<?php

namespace App\Http\Controllers;

use App\Area;
use App\CapturedTractor;
use App\Exports\PeriodicServiceExport;
use App\Exports\PeriodicServiceReportExport;
use App\InvoiceCustomerList;
use App\PeriodicServiceHistories;
use App\PeriodicServices;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

class PeriodicServiceController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function dashboard(Request $request)
    {
        if (Auth::user()->role_id == 1){
            $engineers = User::where('role_id',2)->get();
        }else{
            $engineers = User::where('role_id',2)->where('username',Auth::user()->username)->get();
        }

        if ($request->has('username') && isset($request->username)){
            $user = User::where('username',$request->username)->first();
            $user_depot = $user->Depot;
        }else{
            $user_depot = Auth::user()->Depot;
        }

        $from_date = $request->from_date ? date('Y-m-d',strtotime($request->from_date)) : Carbon::now()->format('Y-m-d');

        $to_date = $request->to_date ? date('Y-m-d',strtotime($request->to_date)) : Carbon::now()->endOfMonth()->format('Y-m-d');

        $pending = DB::select("SELECT 
                                    *
                                From (
                                select 
                                row_number() over(partition by i.chassisno order by service_date desc) sl,
                                i.*, psh.service_date,psh.next_service_date,psh.service_hour,ps.service_hour as ps_hour  from InvoiceCustomerList i
                                left  join periodic_service_histories psh on i.ChassisNo=psh.chassisno
                                left join periodic_services ps on ps.id = psh.periodic_service_id+1
                                where i.ChassisDouble=0 and CustomerCode like '$user_depot%'
                                ) S where sl = 1  AND next_service_date BETWEEN '".$from_date."' AND '".$to_date."'
                                order by CustomerCode asc");
                                
        $pending = collect($pending)->count();

        $expired = DB::select("SELECT 
                                    *
                                From (
                                select 
                                row_number() over(partition by i.chassisno order by service_date desc) sl,
                                i.*, psh.service_date,psh.next_service_date,psh.service_hour,ps.service_hour as ps_hour  from InvoiceCustomerList i
                                left  join periodic_service_histories psh on i.ChassisNo=psh.chassisno
                                left join periodic_services ps on ps.id = psh.periodic_service_id+1
                                where i.ChassisDouble=0 and CustomerCode like '$user_depot%'
                                ) S where sl = 1  AND next_service_date < '".$from_date."'
                                order by CustomerCode asc");
        $expired = collect($expired)->count();

        $doneThisMonth = PeriodicServiceHistories::whereBetween('service_date',[Carbon::now()->startOfMonth()->format('Y-m-d'),Carbon::now()->endOfMonth()->format('Y-m-d')])
            ->where('customer_code', 'like', $user_depot.'%')
            ->count();
        $estimatedTotalService = $pending + $expired;

        $service_id_count = DB::select("SELECT  
                                            periodic_service_id, count([id]) count_id
                                            FROM [MotorServiceAuto].[dbo].[periodic_service_histories]
                                            where service_date between '".$from_date."' and '".$to_date."'
                                            and customer_code like '$user_depot%'
                                            GROUP BY periodic_service_id order by periodic_service_id asc");
        $service_id_count = array_column($service_id_count,'count_id');

        $service_label_count = DB::select("SELECT  
                                            psh.periodic_service_id, ps.service_hour
                                            FROM [MotorServiceAuto].[dbo].[periodic_service_histories] psh
                                            left join periodic_services ps on ps.id=psh.periodic_service_id 
                                            where service_date between '".$from_date."' and '".$to_date."'
                                            and customer_code like '$user_depot%'
                                            group by psh.periodic_service_id,ps.service_hour
                                            order by psh.periodic_service_id asc");
        $service_label_count = array_column($service_label_count,'service_hour');

       $service_done = DB::select("SELECT  
                                        service_done_by, count([id]) count_id
                                        FROM [MotorServiceAuto].[dbo].[periodic_service_histories]
                                        where service_date between '".$from_date."' and '".$to_date."'
                                        and customer_code like '$user_depot%'
                                        GROUP BY service_done_by");
        $service_done_by = array_column($service_done,'service_done_by');
        $service_done_count = array_column($service_done,'count_id');

        //IN TIME
        $onTime = DB::select("SELECT COUNT(*) OnTime
                    FROM periodic_service_histories psh1 
                    WHERE periodic_service_id = (SELECT MAX(psh2.periodic_service_id) FROM periodic_service_histories AS psh2 WHERE psh1.chassisno = psh2.chassisno)
                    and psh1.customer_code like '$user_depot%' 
                    AND FORMAT(psh1.service_date,'yyyy-MM') = FORMAT(GETDATE(),'yyyy-MM')
                    AND (FORMAT(service_date,'yyyy-MM') = FORMAT(previous_service_date,'yyyy-MM'))
                    AND FORMAT(next_service_date,'yyyy-MM-dd') >= FORMAT(GETDATE(),'yyyy-MM-dd')");
        $onTime = $onTime[0]->OnTime;
        //EARLY
        $early = DB::select("SELECT COUNT(*) Early
                    FROM periodic_service_histories psh1 
                    WHERE periodic_service_id = (SELECT MAX(psh2.periodic_service_id) FROM periodic_service_histories as psh2 WHERE psh1.chassisno = psh2.chassisno)
                    and psh1.customer_code like '$user_depot%' 
                    AND FORMAT(psh1.service_date,'yyyy-MM') = FORMAT(GETDATE(),'yyyy-MM')  
                    AND (FORMAT(service_date,'yyyy-MM') < FORMAT(previous_service_date,'yyyy-MM'))");
        $early = $early[0]->Early;
        $delay = DB::select("SELECT COUNT(*) Delay
                            FROM periodic_service_histories psh1 
                            WHERE periodic_service_id = (SELECT MAX(psh2.periodic_service_id) FROM periodic_service_histories as psh2 WHERE psh1.chassisno = psh2.chassisno)
                            and psh1.customer_code like '$user_depot%' 
                            AND FORMAT(psh1.service_date,'yyyy-MM') = FORMAT(GETDATE(),'yyyy-MM')
                            AND FORMAT(psh1.service_date,'yyyy-MM-dd') > FORMAT(psh1.previous_service_date,'yyyy-MM-dd')
                            AND psh1.periodic_service_id > '1'");
        $delay = $delay[0]->Delay;

        return view('periodic_service.dashboard', compact('engineers','pending','expired','estimatedTotalService','doneThisMonth','service_id_count','service_label_count','service_done_by','service_done_count','onTime','early','delay'));
    }
    public function index()
    {
        $user_depot = Auth::user()->Depot;
        $services = InvoiceCustomerList::where('chassisDouble', 0)->where('CustomerCode', 'like', $user_depot.'%')
            ->orderBy('InvoiceDate','asc')->with('captured_tractor')->paginate(10);
        //dd($services);

        return view("periodic_service.service_list", compact('services'));
    }

    public function customerSearchByCode(Request $request)
    {
//        $pending = DB::select("SELECT
//                                    *
//                                From (
//                                select
//                                row_number() over(partition by i.chassisno order by service_date desc) sl,
//                                i.*, psh.service_date,psh.next_service_date,psh.service_hour,ps.service_hour as ps_hour  from InvoiceCustomerList i
//                                left  join periodic_service_histories psh on i.ChassisNo=psh.chassisno
//                                left join periodic_services ps on ps.id = psh.periodic_service_id+1
//                                where i.ChassisDouble=0
//                                ) S where sl = 1  AND next_service_date BETWEEN '".date('Y-m-d')."' AND '".date('Y-m-31')."'
//                                order by CustomerCode asc");
//
//        $pending = collect($pending)->count();
//        $expired = DB::select("SELECT
//                                    *
//                                From (
//                                select
//                                row_number() over(partition by i.chassisno order by service_date desc) sl,
//                                i.*, psh.service_date,psh.next_service_date,psh.service_hour,ps.service_hour as ps_hour  from InvoiceCustomerList i
//                                left  join periodic_service_histories psh on i.ChassisNo=psh.chassisno
//                                left join periodic_services ps on ps.id = psh.periodic_service_id+1
//                                where i.ChassisDouble=0
//                                ) S where sl = 1  AND next_service_date < '".date('Y-m-d')."'
//                                order by CustomerCode asc");
//        $expired = collect($expired)->count();
        
        if($request->has('search')){
            Session::put('search', $request->search);
        }

        $search = Session::get('search');
        if($search != ""){
            $services = InvoiceCustomerList::where('chassisDouble', 0)->where('CustomerCode',Session::get('search'))->orWhere('ChassisNo','like','%'.Session::get('search').'%')->orderBy('CustomerCode','asc')->paginate(10);
        }else{
            $services = InvoiceCustomerList::where('chassisDouble', 0)->orderBy('CustomerCode','asc')->paginate(10);
        }

        return view("periodic_service.service_list", compact('services','search'));
    }

    public function showPeriodicServicePage()
    {
        // $chassisNos = PeriodicServiceHistories::select('chassisno')->distinct()->get();

        return view("periodic_service.services");
    }

    public function searchByChassisNumber(Request $request){
        $search = $request->search;

        if($search == ''){
           $chassisNos = [];
        }else{
           $chassisNos = PeriodicServiceHistories::select('chassisno')->distinct()->where('chassisno', 'like', '%' .$search . '%')->limit(5)->get();
        }
  
        $response = array();
        foreach($chassisNos as $ch){
           $response[] = $ch->chassisno;
        }
        return response()->json($response); 
     } 
     
     public function searchByChassisNumInvCustomList(Request $request){
        $search = $request->search;
         $user_depot = Auth::user()->Depot;
        if($search == ''){
           $chassisNos = [];
        }else{
           $chassisNos = InvoiceCustomerList::select('ChassisNo')->distinct()->where('ChassisNo', 'like', '%' .$search . '%')->where('CustomerCode', 'like', $user_depot.'%')->limit(5)->get();
        }
  
        $response = array();
        foreach($chassisNos as $ch){
           $response[] = $ch->ChassisNo;
        }
        return response()->json($response); 
     } 

    public function serviceList(Request $request)
    {
        $chassis = $request->chassisno;
        $periodicServices = PeriodicServiceHistories::with('periodic_service')->where('Chassisno',$chassis)->get();

        $services = InvoiceCustomerList::where('ChassisNo', $request->chassisno)->orderBy('InvoiceDate','desc')->get();

        return view("periodic_service.services", compact('periodicServices', 'services','chassis'));
    }

    public function syncCustomer()
    {
        $sync = DB::connection("MotorBrInvoiceMirror")->statement("exec usp_doLoadCustomerListForJobHistory");
        Session::flash("message", "Data Synced Successfully");
        return redirect(route('periodic-service.list'));
    }

    public function create(Request $request)
    {
        $chassis = $request->chassis;
        $service = PeriodicServiceHistories::where('ChassisNo', $chassis)->orderBy('created_at','desc')->first();
        $invoice = InvoiceCustomerList::where('ChassisNo', $chassis)->orderBy('InvoiceDate','asc')->first();
        $customers = InvoiceCustomerList::select('CustomerCode','CustomerName1')->where('ChassisNo',$chassis)->get();
        $areas = Area::all();

        if(empty($service)){
            $service = new PeriodicServiceHistories();
            $service->periodic_service_id = 0;
        }
        $service->periodic_service_id = $service->periodic_service_id + 1;
        
        if($service->periodic_service_id > 21){
            return redirect(route('periodic-service.list'))->with('error', 'You have reached Maximum Service Limit !');
        }
        if($service->service_hour > 5000){
            return redirect(route('periodic-service.list'))->with('error', 'You have reached Maximum Service Hour (5000 Hr) Limit !');
        }

        $date=date_create($invoice->InvoiceDate);
        $serviceYearLimit = date_add($date,date_interval_create_from_date_string("5 years"));
        
        if($serviceYearLimit < Carbon::now()){
            return redirect(route('periodic-service.list'))->with('error', 'You have reached Maximum Service Years (5 Years) Limit !');
        }
        
        return view("periodic_service.add_new_service", compact('service','chassis','customers','areas'));
    }

    public function testInsert()
    {
        $services = PeriodicServiceHistories::where('periodic_service_id','1')->get();
        foreach ($services as $service) {
            $serv = PeriodicServiceHistories::find($service->id);
            $currentDate = Carbon::parse($serv->service_date);
            //$previousData = PeriodicServiceHistories::where('chassisno',$service->chassisno)->where('periodic_service_id',$service->periodic_service_id - 1)->orderBy('periodic_service_id','desc')->first();
            //$serv->previous_service_date = $previousData->next_service_date;
            $serv->previous_service_date = $currentDate->format('Y-m-d');
            $serv->save();
        }
        dd("OK");
    }

    public function store(Request $request)
    {
        $date=date_create($request->service_date);
        //PREVIOUS SERVICE DATE SET
        $previousServiceDate = null;
        $serviceHistory = PeriodicServiceHistories::where('chassisno',$request->chassis)->orderBy('id','desc')->first();
        if (intval($request->periodic_service_id) > 1) {
            $previousServiceDate = $serviceHistory->next_service_date;
        } else {
            $previousServiceDate = $request->service_date;
        }
        //NEXT SERVICE DATE SET
        $next_service_date = date_add($date,date_interval_create_from_date_string("60 days"));
        $service = new PeriodicServiceHistories();
        $service->customer_code = $request->customer_code;
        $service->customer_name = $request->customer_name;
        $service->customer_address = $request->customer_address;
        $service->periodic_service_id = $request->periodic_service_id;
        $service->chassisno = $request->chassis;
        $service->previous_service_date = $previousServiceDate;
        $service->service_date = $request->service_date;
        $service->next_service_date = $next_service_date;
        $service->service_hour = $request->service_hour;
        $service->service_taken_place = $request->service_taken_place;
        $service->service_done_by = $request->service_done_by;
        $service->service_done_by_name = $request->service_done_by_name;
        $service->remarks = $request->remarks;
        $service->area_id = $request->area_id;
        $service->status = 1;
        $service->save();

        return redirect(route('periodic-service.list'))->with('message', 'Service Added Successfully');
    }

    public function getCustomerInfoByCode(Request $request)
    {
        $CustomerCode = $request->CustomerCode;
        $customer = InvoiceCustomerList::where('CustomerCode', $CustomerCode)->first();
        return response()->json($customer);
    }

    public function edit($id)
    {
        $service = PeriodicServiceHistories::where('id', $id)->first();
        $customers = InvoiceCustomerList::select('CustomerCode','CustomerName1')->where('ChassisNo',$service->chassisno)->get();
        return view('periodic_service.edit_service', compact('service','customers'));
    }

    public function update(Request $request)
    {
        $date=date_create($request->service_date);
        $next_service_date = date_add($date,date_interval_create_from_date_string("60 days"));

        $service = PeriodicServiceHistories::where('id',$request->id)->first();
        $service->service_date = $request->service_date;
        $service->next_service_date = $next_service_date;
        $service->service_hour = $request->service_hour;
        $service->service_taken_place = $request->service_taken_place;
        $service->service_done_by = $request->service_done_by;
        $service->service_done_by_name = $request->service_done_by_name;
        $service->customer_code = $request->customer_code;
        $service->customer_name = $request->customer_name;
        $service->customer_address = $request->customer_address;
        $service->remarks = $request->remarks;
        $service->save();

        $chassis = $service->chassisno;
        $periodicServices = PeriodicServiceHistories::with('periodic_service')->where('Chassisno',$chassis)->get();
        $services = InvoiceCustomerList::where('ChassisNo', $chassis)->orderBy('InvoiceDate','desc')->get();
        Session::flash('message','Service Updated Successfully');
        return view("periodic_service.services", compact('periodicServices','chassis','services'));
        // return redirect(route('periodic-service.list'))->with('message', 'Service Updated Successfully');
    }

    public function destroy(Request $request)
    {
        $chassis = $request->chassis_no;
        PeriodicServiceHistories::where('id',$request->delete_id)->delete();
        $periodicServices = PeriodicServiceHistories::with('periodic_service')->where('Chassisno',$chassis)->get();
        Session::flash('message','Service Deleted Successfully');
        return view("periodic_service.services", compact('periodicServices','chassis'));
        // return redirect(route('periodic-service.list'))->with('message', 'Service Deleted Successfully');
    }

    public function showCustomerInfoPage()
    {
        return view('periodic_service.customer_info');
    }

    public function postCustomerInfo(Request $request)
    {
        $chassis = $request->chassisno;
        $services = InvoiceCustomerList::where('ChassisNo', $request->chassisno)->orderBy('InvoiceDate','desc')->get();

        return view("periodic_service.customer_info", compact('services','chassis'));
    }

    public function showNextServiceInfoPage(Request $request)
    {
        $user_depot = Auth::user()->Depot;

        $dateFrom = '';
        $dateTo = '';
        $address = '';
        $Status = '';

        $perPage = 10;
        $page = $request->input("page", 1);
        $skip = $page == 1 ? (($page-1) * $perPage) : (($page-1) * $perPage) + 1;
        $take = $page == 1 ? $page * $perPage : ($page * $perPage) + 1;
        if($take < 1) { $take = 1; }
        if($skip < 0) { $skip = 0; }

        $services = DB::select("SELECT 
            *
          From (
          select 
          row_number() over(partition by i.chassisno order by service_date desc) sl,
          i.*, psh.service_date,psh.next_service_date,psh.service_hour,ps.service_hour as ps_hour  from InvoiceCustomerList i
          left  join periodic_service_histories psh on i.ChassisNo=psh.chassisno
          left join periodic_services ps on ps.id = psh.periodic_service_id+1
          where i.ChassisDouble=0 and CustomerCode like '$user_depot%'
          ) S where sl = 1 order by CustomerCode asc");

        $totalCount = collect($services)->count();
        $results = collect($services)
        ->take($take)
        ->skip($skip);

        $services = new \Illuminate\Pagination\LengthAwarePaginator($results, $totalCount, 10, $page);
        $services->setPath(URL::to('show-next-service-info-page'));

        return view('periodic_service.next_service_info', compact('services','dateFrom','dateTo','address','Status'));
    }

    public function searchNextServiceByDate(Request $request)
    {
        $user_depot = Auth::user()->Depot;
        $dateFrom = $request->dateFrom;
        $dateTo = $request->dateTo;
        $address = $request->address;
        $Status = $request->Status;
//dd($dateFrom);
        if($Status=='Pending'){
            $string = "AND next_service_date >= '".date('Y-m-d')."'";
        }
        if($Status=='Expired'){
            $string = "AND next_service_date < '".date('Y-m-d')."'";
        }

        if($Status==""){
            $string = "";
        }

        $perPage = 10;
        $page = $request->input("page", 1);
        $skip = $page == 1 ? (($page-1) * $perPage) : (($page-1) * $perPage) + 1;
        $take = $page == 1 ? $page * $perPage : ($page * $perPage) + 1;
        if($take < 1) { $take = 1; }
        if($skip < 0) { $skip = 0; }

        $services = DB::select("SELECT 
            *
          From (
          select 
          row_number() over(partition by i.chassisno order by service_date desc) sl,
          i.*, psh.service_date,psh.next_service_date,psh.service_hour,ps.service_hour as ps_hour  from InvoiceCustomerList i
          left  join periodic_service_histories psh on i.ChassisNo=psh.chassisno
          left join periodic_services ps on ps.id = psh.periodic_service_id+1
          where i.ChassisDouble=0 and CustomerCode like '$user_depot%'
          ) S where sl = 1 $string AND Address1 like '%$address%' AND next_service_date BETWEEN '$dateFrom' AND '$dateTo'
          order by CustomerCode asc");

        $totalCount = collect($services)->count();
        $results = collect($services)
        ->take($take)
        ->skip($skip);

        $services = new \Illuminate\Pagination\LengthAwarePaginator($results, $totalCount, 10, $page);
        $services->setPath(URL::to("search-next-service-by-date?dateFrom=$dateFrom&dateTo=$dateTo"));

        return view('periodic_service.next_service_info', compact('services','dateFrom','dateTo','address','Status'));
    }

    public function searchServiceByAddress(Request $request)
    {
        $user_depot = Auth::user()->Depot;
        $dateFrom = '';
        $dateTo = '';
        $address = $request->address;
        $Status = '';

        $perPage = 10;
        $page = $request->input("page", 1);
        $skip = $page == 1 ? (($page-1) * $perPage) : (($page-1) * $perPage) + 1;
        $take = $page == 1 ? $page * $perPage : ($page * $perPage) + 1;
        if($take < 1) { $take = 1; }
        if($skip < 0) { $skip = 0; }

        $services = DB::select("SELECT 
            *
          From (
          select 
          row_number() over(partition by i.chassisno order by service_date desc) sl,
          i.*, psh.service_date,psh.next_service_date,psh.service_hour,ps.service_hour as ps_hour  from InvoiceCustomerList i
          left  join periodic_service_histories psh on i.ChassisNo=psh.chassisno
          left join periodic_services ps on ps.id = psh.periodic_service_id+1
          where i.ChassisDouble=0 and CustomerCode like '$user_depot%'
          ) S where sl = 1 AND Address1 like '%$address%'
          order by CustomerCode asc");

        $totalCount = collect($services)->count();
        $results = collect($services)
        ->take($take)
        ->skip($skip);

        $services = new \Illuminate\Pagination\LengthAwarePaginator($results, $totalCount, 10, $page);
        $services->setPath(URL::to("search-service-by-address?address=$address"));

        return view('periodic_service.next_service_info', compact('services','dateFrom','dateTo','address','Status'));
    }
    
    public function searchServiceByStatus(Request $request)
    {
        $user_depot = Auth::user()->Depot;
        $dateFrom = '';
        $dateTo = '';
        $address = '';
        $Status = $request->Status;
        $string = '';

        if($Status=='Pending'){
            $string = "AND next_service_date >= '".date('Y-m-d')."'";
        }
        if($Status=='Expired'){
            $string = "AND next_service_date < '".date('Y-m-d')."'";
        }

        if($Status==""){
            $string = "";
        }

        $perPage = 10;
        $page = $request->input("page", 1);
        $skip = $page == 1 ? (($page-1) * $perPage) : (($page-1) * $perPage) + 1;
        $take = $page == 1 ? $page * $perPage : ($page * $perPage) + 1;
        if($take < 1) { $take = 1; }
        if($skip < 0) { $skip = 0; }

        $services = DB::select("SELECT 
            *
          From (
          select 
          row_number() over(partition by i.chassisno order by service_date desc) sl,
          i.*, psh.service_date,psh.next_service_date,psh.service_hour,ps.service_hour as ps_hour  from InvoiceCustomerList i
          left  join periodic_service_histories psh on i.ChassisNo=psh.chassisno
          left join periodic_services ps on ps.id = psh.periodic_service_id+1
          where i.ChassisDouble=0 and CustomerCode like '$user_depot%'
          ) S where sl = 1  $string
          order by CustomerCode asc");
        $totalCount = collect($services)->count();
        $results = collect($services)
        ->take($take)
        ->skip($skip);

        $services = new \Illuminate\Pagination\LengthAwarePaginator($results, $totalCount, 10, $page);
        $services->setPath(URL::to("search-service-by-status?Status=$Status"));

        return view('periodic_service.next_service_info', compact('services','dateFrom','dateTo','address','Status'));
    }

    public function tractorCaptured(Request $request)
    {
        $captured = new CapturedTractor();
        $captured->chassis_no = $request->chassisnumber;
        $captured->captured_date = Carbon::now();
        $captured->save();
        
        return redirect(route('periodic-service.list'));
    }

    public function exportPeriodicService(Request $request)
    {
        return Excel::download(new PeriodicServiceExport($request), 'periodic_service_list.xlsx');
    }

    public function exportPeriodicReport(Request $request)
    {
        return Excel::download(new PeriodicServiceReportExport($request), 'periodic_service_report-'.Carbon::now()->format('Ymd').'.xlsx');
    }

    public function showPeriodicReport(Request $request)
    {
        $user_depot = Auth::user()->Depot;
        $dateFrom = $request->dateFrom;
        $dateTo = $request->dateTo;
        $status = $request->status;
        $address = '';

        $query = DB::table('periodic_service_histories as psh1')
            ->whereRaw("periodic_service_id = (SELECT MAX(psh2.periodic_service_id) FROM periodic_service_histories AS psh2 WHERE psh1.chassisno = psh2.chassisno) ");
        if ($dateFrom != '' && $dateTo != '') {
            $query->whereBetween('service_date',[Carbon::parse($dateFrom)->format('Y-m-d'),Carbon::parse($dateTo)->format('Y-m-d')]);
        }
        if ($status === 'onTime') {
            $query->whereRaw("psh1.periodic_service_id > '1' AND FORMAT(psh1.service_date,'yyyy-MM') = FORMAT(psh1.previous_service_date,'yyyy-MM')");
        }
        elseif ($status === 'early') {
            $query->whereRaw("psh1.periodic_service_id > '1' AND FORMAT(psh1.service_date,'yyyy-MM') < FORMAT(psh1.previous_service_date,'yyyy-MM')");
        }
        elseif ($status === 'delay') {
            $query->whereRaw("psh1.periodic_service_id > '1' AND FORMAT(psh1.service_date,'yyyy-MM-dd') > FORMAT(psh1.previous_service_date,'yyyy-MM-dd')");
        }
        $query->select('psh1.customer_code','psh1.customer_name','psh1.customer_address','psh1.chassisno',
                DB::raw(
                    "CAST(
                        CASE 
                            WHEN psh1.periodic_service_id = '1'
                                THEN 'First Service'
                                ELSE CONVERT(varchar(50),FORMAT(psh1.previous_service_date,'dd-MM-yyyy'))
                            END as varchar(50)
                    ) as pre_date"
                ),
                DB::raw("FORMAT(psh1.service_date,'dd-MM-yyyy') as service_date"),
                DB::raw(
                    "CAST(
                        CASE
                            WHEN psh1.periodic_service_id > '1' AND FORMAT(psh1.service_date,'yyyy-MM') = FORMAT(psh1.previous_service_date,'yyyy-MM')
                                THEN 'ON-TIME'
                                WHEN psh1.periodic_service_id > '1' AND FORMAT(psh1.service_date,'yyyy-MM') < FORMAT(psh1.previous_service_date,'yyyy-MM')
                                THEN 'EARLY'
                                WHEN psh1.periodic_service_id > '1' AND FORMAT(psh1.service_date,'yyyy-MM-dd') > FORMAT(psh1.previous_service_date,'yyyy-MM-dd')
                                THEN 'EXPIRED'
                                WHEN psh1.periodic_service_id = '1'
                                THEN 'FIRST SERVICE'
                            END as varchar(50)
                    ) as status"
                )
            );
        $services = $query->where('customer_code', 'like', $user_depot.'%')->paginate();
        return view('periodic_service.report',['services' => $services,'dateFrom' => $dateFrom,'dateTo' => $dateTo,'status' => $status]);
    }
}
