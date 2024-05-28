<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\TechnicianKpiAdjust;
use App\User;
use App\Http\Requests\TechnicianKpiAdjustStoreRequest;
use App\Http\Requests\TechnicianKpiAdjustUpdateRequest;


class TechnicianKpiAdjustController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }

    public function index(Request $request){
        if($request->date){
          $date = date('Y-m-d',strtotime($request->date));
        }else{
          $date = date('Y-m-d');
        }
        $technician_kpi_adjusts = TechnicianKpiAdjust::orderBy('id','Desc')
                                ->whereMonth('date',date('m',strtotime($date)))
                                ->whereYear('date',date('Y',strtotime($date)))
                                ->get();
        return view("technician_kpi_adjust.technician_kpi_adjust_list",compact("technician_kpi_adjusts"));
    }


    public function create(){
        
        $users=User::where('kpi_type_id',3)->get();
        return view("technician_kpi_adjust.technician_kpi_adjust_create")
             ->with("users" ,$users);;
    }


    public function store(TechnicianKpiAdjustStoreRequest $request){

        $technician_kpi_adjust= new TechnicianKpiAdjust;
        $technician_kpi_adjust->date=date("Y-m-d",strtotime($request->date));
        $technician_kpi_adjust->user_id=$request->user_id;
        $technician_kpi_adjust->service_ratio_ws_actual=$request->service_ratio_ws_actual;
        $technician_kpi_adjust->service_ratio_pws_actual=$request->service_ratio_pws_actual;
        $technician_kpi_adjust->satisfaction_index_six_actual=$request->satisfaction_index_six_actual;
        $technician_kpi_adjust->satisfaction_index_six_target=$request->satisfaction_index_six_target;
        $technician_kpi_adjust->satisfaction_index_csi_actual=$request->satisfaction_index_csi_actual;
        $technician_kpi_adjust->satisfaction_index_csi_target=$request->satisfaction_index_csi_target;
        $technician_kpi_adjust->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/technician_kpi_adjust");
    }



    public function edit($id){
        
        $users=User::where('kpi_type_id',3)->get();
        $technician_kpi_adjust = TechnicianKpiAdjust::findOrFail($id);
        return view("technician_kpi_adjust.technician_kpi_adjust_edit",compact("technician_kpi_adjust"))
             ->with("users" ,$users);;

    }

    public function update(TechnicianKpiAdjustUpdateRequest $request, $id) {

        $technician_kpi_adjust=TechnicianKpiAdjust::findOrFail($id);
        $technician_kpi_adjust->date=date("Y-m-d",strtotime($request->date));
        $technician_kpi_adjust->user_id=$request->user_id;
        $technician_kpi_adjust->service_ratio_ws_actual=$request->service_ratio_ws_actual;
        $technician_kpi_adjust->service_ratio_pws_actual=$request->service_ratio_pws_actual;
        $technician_kpi_adjust->satisfaction_index_six_actual=$request->satisfaction_index_six_actual;
        $technician_kpi_adjust->satisfaction_index_six_target=$request->satisfaction_index_six_target;
        $technician_kpi_adjust->satisfaction_index_csi_actual=$request->satisfaction_index_csi_actual;
        $technician_kpi_adjust->satisfaction_index_csi_target=$request->satisfaction_index_csi_target;
        $technician_kpi_adjust->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/technician_kpi_adjust");
    }

    public function show($id){
        $technician_kpi_adjust = TechnicianKpiAdjust::find($id);
        return view("technician_kpi_adjust.technician_kpi_adjust_show",compact("technician_kpi_adjust"));
    }


    public function destroy($id){
        $technician_kpi_adjust = TechnicianKpiAdjust::findOrFail($id);
        $technician_kpi_adjust ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/technician_kpi_adjust");
    }

}
