<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Kpia;
use App\User;
use App\KpiType;
use App\Http\Requests\KpiaStoreRequest;
use App\Http\Requests\KpiaUpdateRequest;


class KpiaController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $kpias = Kpia::orderBy('id','Desc')->paginate(20);
        return view("kpia.kpia_list",compact("kpias"));
    }


    public function create(){
        
        $users=User::all();
        $kpi_types=KpiType::all();
        return view("kpia.kpia_create")
             ->with("users" ,$users)
             ->with("kpi_types" ,$kpi_types);;
    }


    public function store(KpiaStoreRequest $request){

        $kpia= new Kpia;
        $kpia->date=date("Y-m-d",strtotime($request->date));
        $kpia->user_id=$request->user_id;
        $kpia->kpi_type_id=$request->kpi_type_id;
        $kpia->total_incentive_bonus=$request->total_incentive_bonus;
        $kpia->total_kpi_mark=$request->total_kpi_mark;
        $kpia->total_incentive_amount=$request->total_incentive_amount;
        $kpia->service_ratio_ws_target=$request->service_ratio_ws_target;
        $kpia->service_ratio_ws_actual=$request->service_ratio_ws_actual;
        $kpia->service_ratio_ws_weight=$request->service_ratio_ws_weight;
        $kpia->service_ratio_ws_score=$request->service_ratio_ws_score;
        $kpia->service_ratio_ws_f_score=$request->service_ratio_ws_f_score;
        $kpia->service_ratio_pws_target=$request->service_ratio_pws_target;
        $kpia->service_ratio_pws_actual=$request->service_ratio_pws_actual;
        $kpia->service_ratio_pws_weight=$request->service_ratio_pws_weight;
        $kpia->service_ratio_pws_score=$request->service_ratio_pws_score;
        $kpia->service_ratio_pws_f_score=$request->service_ratio_pws_f_score;
        $kpia->satisfaction_index_six_target=$request->satisfaction_index_six_target;
        $kpia->satisfaction_index_six_actual=$request->satisfaction_index_six_actual;
        $kpia->satisfaction_index_six_weight=$request->satisfaction_index_six_weight;
        $kpia->satisfaction_index_six_score=$request->satisfaction_index_six_score;
        $kpia->satisfaction_index_six_f_score=$request->satisfaction_index_six_f_score;
        $kpia->satisfaction_index_csi_target=$request->satisfaction_index_csi_target;
        $kpia->satisfaction_index_csi_actual=$request->satisfaction_index_csi_actual;
        $kpia->satisfaction_index_csi_weight=$request->satisfaction_index_csi_weight;
        $kpia->satisfaction_index_csi_score=$request->satisfaction_index_csi_score;
        $kpia->satisfaction_index_csi_f_score=$request->satisfaction_index_csi_f_score;
        $kpia->service_income_target=$request->service_income_target;
        $kpia->service_income_actual=$request->service_income_actual;
        $kpia->service_income_weight=$request->service_income_weight;
        $kpia->service_income_score=$request->service_income_score;
        $kpia->service_income_f_score=$request->service_income_f_score;
        $kpia->report_submission_target=$request->report_submission_target;
        $kpia->report_submission_actual=$request->report_submission_actual;
        $kpia->report_submission_weight=$request->report_submission_weight;
        $kpia->report_submission_score=$request->report_submission_score;
        $kpia->report_submission_f_score=$request->report_submission_f_score;
        $kpia->app_monitor_target=$request->app_monitor_target;
        $kpia->app_monitor_actual=$request->app_monitor_actual;
        $kpia->app_monitor_weight=$request->app_monitor_weight;
        $kpia->app_monitor_score=$request->app_monitor_score;
        $kpia->app_monitor_f_score=$request->app_monitor_f_score;
        $kpia->team_co_target=$request->team_co_target;
        $kpia->team_co_actual=$request->team_co_actual;
        $kpia->team_co_weight=$request->team_co_weight;
        $kpia->team_co_score=$request->team_co_score;
        $kpia->team_co_f_score=$request->team_co_f_score;
        $kpia->service_income_base_line=$request->service_income_base_line;
        $kpia->service_f_score_total=$request->service_f_score_total;
        $kpia->service_f_score_percent=$request->service_f_score_percent;
        $kpia->service_income_total_incentive=$request->service_income_total_incentive;
        $kpia->sp_tractor_target=$request->sp_tractor_target;
        $kpia->sp_tractor_actual=$request->sp_tractor_actual;
        $kpia->sp_tractor_weight=$request->sp_tractor_weight;
        $kpia->sp_tractor_score=$request->sp_tractor_score;
        $kpia->sp_tractor_f_score=$request->sp_tractor_f_score;
        $kpia->sp_tractor_base_line=$request->sp_tractor_base_line;
        $kpia->sp_tractor_f_score_total=$request->sp_tractor_f_score_total;
        $kpia->sp_tractor_f_score_percent=$request->sp_tractor_f_score_percent;
        $kpia->sp_tractor_total_incentive=$request->sp_tractor_total_incentive;
        $kpia->sp_nmpt_target=$request->sp_nmpt_target;
        $kpia->sp_nmpt_actual=$request->sp_nmpt_actual;
        $kpia->sp_nmpt_weight=$request->sp_nmpt_weight;
        $kpia->sp_nmpt_score=$request->sp_nmpt_score;
        $kpia->sp_nmpt_f_score=$request->sp_nmpt_f_score;
        $kpia->sp_nmpt_base_line=$request->sp_nmpt_base_line;
        $kpia->sp_nmpt_f_score_total=$request->sp_nmpt_f_score_total;
        $kpia->sp_nmpt_f_score_percent=$request->sp_nmpt_f_score_percent;
        $kpia->sp_nmpt_total_incentive=$request->sp_nmpt_total_incentive;
        $kpia->sp_tractor_plus_nmpt_target=$request->sp_tractor_plus_nmpt_target;
        $kpia->sp_tractor_plus_nmpt_actual=$request->sp_tractor_plus_nmpt_actual;
        $kpia->sp_tractor_plus_nmpt_weight=$request->sp_tractor_plus_nmpt_weight;
        $kpia->sp_tractor_plus_nmpt_score=$request->sp_tractor_plus_nmpt_score;
        $kpia->sp_tractor_plus_nmpt_f_score=$request->sp_tractor_plus_nmpt_f_score;
        $kpia->sp_tractor_plus_nmpt_base_line=$request->sp_tractor_plus_nmpt_base_line;
        $kpia->sp_tractor_plus_nmpt_f_score_total=$request->sp_tractor_plus_nmpt_f_score_total;
        $kpia->sp_tractor_plus_nmpt_f_score_percent=$request->sp_tractor_plus_nmpt_f_score_percent;
        $kpia->sp_tractor_plus_nmpt_total_incentive=$request->sp_tractor_plus_nmpt_total_incentive;
        $kpia->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/kpia");
    }



    public function edit($id){
        
        $users=User::all();
        $kpi_types=KpiType::all();
        $kpia = Kpia::findOrFail($id);
        return view("kpia.kpia_edit",compact("kpia"))
             ->with("users" ,$users)
             ->with("kpi_types" ,$kpi_types);;

    }

    public function update(KpiaUpdateRequest $request, $id) {

        $kpia=Kpia::findOrFail($id);
        $kpia->date=date("Y-m-d",strtotime($request->date));
        $kpia->user_id=$request->user_id;
        $kpia->kpi_type_id=$request->kpi_type_id;
        $kpia->total_incentive_bonus=$request->total_incentive_bonus;
        $kpia->total_kpi_mark=$request->total_kpi_mark;
        $kpia->total_incentive_amount=$request->total_incentive_amount;
        $kpia->service_ratio_ws_target=$request->service_ratio_ws_target;
        $kpia->service_ratio_ws_actual=$request->service_ratio_ws_actual;
        $kpia->service_ratio_ws_weight=$request->service_ratio_ws_weight;
        $kpia->service_ratio_ws_score=$request->service_ratio_ws_score;
        $kpia->service_ratio_ws_f_score=$request->service_ratio_ws_f_score;
        $kpia->service_ratio_pws_target=$request->service_ratio_pws_target;
        $kpia->service_ratio_pws_actual=$request->service_ratio_pws_actual;
        $kpia->service_ratio_pws_weight=$request->service_ratio_pws_weight;
        $kpia->service_ratio_pws_score=$request->service_ratio_pws_score;
        $kpia->service_ratio_pws_f_score=$request->service_ratio_pws_f_score;
        $kpia->satisfaction_index_six_target=$request->satisfaction_index_six_target;
        $kpia->satisfaction_index_six_actual=$request->satisfaction_index_six_actual;
        $kpia->satisfaction_index_six_weight=$request->satisfaction_index_six_weight;
        $kpia->satisfaction_index_six_score=$request->satisfaction_index_six_score;
        $kpia->satisfaction_index_six_f_score=$request->satisfaction_index_six_f_score;
        $kpia->satisfaction_index_csi_target=$request->satisfaction_index_csi_target;
        $kpia->satisfaction_index_csi_actual=$request->satisfaction_index_csi_actual;
        $kpia->satisfaction_index_csi_weight=$request->satisfaction_index_csi_weight;
        $kpia->satisfaction_index_csi_score=$request->satisfaction_index_csi_score;
        $kpia->satisfaction_index_csi_f_score=$request->satisfaction_index_csi_f_score;
        $kpia->service_income_target=$request->service_income_target;
        $kpia->service_income_actual=$request->service_income_actual;
        $kpia->service_income_weight=$request->service_income_weight;
        $kpia->service_income_score=$request->service_income_score;
        $kpia->service_income_f_score=$request->service_income_f_score;
        $kpia->report_submission_target=$request->report_submission_target;
        $kpia->report_submission_actual=$request->report_submission_actual;
        $kpia->report_submission_weight=$request->report_submission_weight;
        $kpia->report_submission_score=$request->report_submission_score;
        $kpia->report_submission_f_score=$request->report_submission_f_score;
        $kpia->app_monitor_target=$request->app_monitor_target;
        $kpia->app_monitor_actual=$request->app_monitor_actual;
        $kpia->app_monitor_weight=$request->app_monitor_weight;
        $kpia->app_monitor_score=$request->app_monitor_score;
        $kpia->app_monitor_f_score=$request->app_monitor_f_score;
        $kpia->team_co_target=$request->team_co_target;
        $kpia->team_co_actual=$request->team_co_actual;
        $kpia->team_co_weight=$request->team_co_weight;
        $kpia->team_co_score=$request->team_co_score;
        $kpia->team_co_f_score=$request->team_co_f_score;
        $kpia->service_income_base_line=$request->service_income_base_line;
        $kpia->service_f_score_total=$request->service_f_score_total;
        $kpia->service_f_score_percent=$request->service_f_score_percent;
        $kpia->service_income_total_incentive=$request->service_income_total_incentive;
        $kpia->sp_tractor_target=$request->sp_tractor_target;
        $kpia->sp_tractor_actual=$request->sp_tractor_actual;
        $kpia->sp_tractor_weight=$request->sp_tractor_weight;
        $kpia->sp_tractor_score=$request->sp_tractor_score;
        $kpia->sp_tractor_f_score=$request->sp_tractor_f_score;
        $kpia->sp_tractor_base_line=$request->sp_tractor_base_line;
        $kpia->sp_tractor_f_score_total=$request->sp_tractor_f_score_total;
        $kpia->sp_tractor_f_score_percent=$request->sp_tractor_f_score_percent;
        $kpia->sp_tractor_total_incentive=$request->sp_tractor_total_incentive;
        $kpia->sp_nmpt_target=$request->sp_nmpt_target;
        $kpia->sp_nmpt_actual=$request->sp_nmpt_actual;
        $kpia->sp_nmpt_weight=$request->sp_nmpt_weight;
        $kpia->sp_nmpt_score=$request->sp_nmpt_score;
        $kpia->sp_nmpt_f_score=$request->sp_nmpt_f_score;
        $kpia->sp_nmpt_base_line=$request->sp_nmpt_base_line;
        $kpia->sp_nmpt_f_score_total=$request->sp_nmpt_f_score_total;
        $kpia->sp_nmpt_f_score_percent=$request->sp_nmpt_f_score_percent;
        $kpia->sp_nmpt_total_incentive=$request->sp_nmpt_total_incentive;
        $kpia->sp_tractor_plus_nmpt_target=$request->sp_tractor_plus_nmpt_target;
        $kpia->sp_tractor_plus_nmpt_actual=$request->sp_tractor_plus_nmpt_actual;
        $kpia->sp_tractor_plus_nmpt_weight=$request->sp_tractor_plus_nmpt_weight;
        $kpia->sp_tractor_plus_nmpt_score=$request->sp_tractor_plus_nmpt_score;
        $kpia->sp_tractor_plus_nmpt_f_score=$request->sp_tractor_plus_nmpt_f_score;
        $kpia->sp_tractor_plus_nmpt_base_line=$request->sp_tractor_plus_nmpt_base_line;
        $kpia->sp_tractor_plus_nmpt_f_score_total=$request->sp_tractor_plus_nmpt_f_score_total;
        $kpia->sp_tractor_plus_nmpt_f_score_percent=$request->sp_tractor_plus_nmpt_f_score_percent;
        $kpia->sp_tractor_plus_nmpt_total_incentive=$request->sp_tractor_plus_nmpt_total_incentive;
        $kpia->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/kpia");
    }

    public function show($id){
        $kpia = Kpia::find($id);
        return view("kpia.kpia_show",compact("kpia"));
    }


    public function destroy($id){
        $kpia = Kpia::findOrFail($id);
        $kpia ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/kpia");
    }

}
