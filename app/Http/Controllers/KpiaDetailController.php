<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\KpiaDetail;
use App\Kpium;
use App\Http\Requests\KpiaDetailStoreRequest;
use App\Http\Requests\KpiaDetailUpdateRequest;


class KpiaDetailController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $kpia_details = KpiaDetail::orderBy('id','Desc')->paginate(20);
        return view("kpia_detail.kpia_detail_list",compact("kpia_details"));
    }


    public function create(){
        
        $kpia=Kpium::all();
        return view("kpia_detail.kpia_detail_create")
             ->with("kpia" ,$kpia);;
    }


    public function store(KpiaDetailStoreRequest $request){

        $kpia_detail= new KpiaDetail;
        $kpia_detail->kpia_id=$request->kpia_id;
        $kpia_detail->service_ratio_ws_target=$request->service_ratio_ws_target;
        $kpia_detail->service_ratio_ws_actual=$request->service_ratio_ws_actual;
        $kpia_detail->service_ratio_ws_weight=$request->service_ratio_ws_weight;
        $kpia_detail->service_ratio_ws_score=$request->service_ratio_ws_score;
        $kpia_detail->service_ratio_ws_f_score=$request->service_ratio_ws_f_score;
        $kpia_detail->service_ratio_pws_target=$request->service_ratio_pws_target;
        $kpia_detail->service_ratio_pws_actual=$request->service_ratio_pws_actual;
        $kpia_detail->service_ratio_pws_weight=$request->service_ratio_pws_weight;
        $kpia_detail->service_ratio_pws_score=$request->service_ratio_pws_score;
        $kpia_detail->service_ratio_pws_f_score=$request->service_ratio_pws_f_score;
        $kpia_detail->satisfaction_index_six_target=$request->satisfaction_index_six_target;
        $kpia_detail->satisfaction_index_six_actual=$request->satisfaction_index_six_actual;
        $kpia_detail->satisfaction_index_six_weight=$request->satisfaction_index_six_weight;
        $kpia_detail->satisfaction_index_six_score=$request->satisfaction_index_six_score;
        $kpia_detail->satisfaction_index_six_f_score=$request->satisfaction_index_six_f_score;
        $kpia_detail->satisfaction_index_csi_target=$request->satisfaction_index_csi_target;
        $kpia_detail->satisfaction_index_csi_actual=$request->satisfaction_index_csi_actual;
        $kpia_detail->satisfaction_index_csi_weight=$request->satisfaction_index_csi_weight;
        $kpia_detail->satisfaction_index_csi_score=$request->satisfaction_index_csi_score;
        $kpia_detail->satisfaction_index_csi_f_score=$request->satisfaction_index_csi_f_score;
        $kpia_detail->service_income_target=$request->service_income_target;
        $kpia_detail->service_income_actual=$request->service_income_actual;
        $kpia_detail->service_income_weight=$request->service_income_weight;
        $kpia_detail->service_income_score=$request->service_income_score;
        $kpia_detail->service_income_f_score=$request->service_income_f_score;
        $kpia_detail->report_submission_weight=$request->report_submission_weight;
        $kpia_detail->report_submission_score=$request->report_submission_score;
        $kpia_detail->report_submission_f_score=$request->report_submission_f_score;
        $kpia_detail->app_monitor_weight=$request->app_monitor_weight;
        $kpia_detail->app_monitor_score=$request->app_monitor_score;
        $kpia_detail->app_monitor_f_score=$request->app_monitor_f_score;
        $kpia_detail->team_co_weight=$request->team_co_weight;
        $kpia_detail->team_co_score=$request->team_co_score;
        $kpia_detail->team_co_f_score=$request->team_co_f_score;
        $kpia_detail->service_income_base_line=$request->service_income_base_line;
        $kpia_detail->service_f_score_total=$request->service_f_score_total;
        $kpia_detail->service_f_score_percent=$request->service_f_score_percent;
        $kpia_detail->service_income_total_incentive=$request->service_income_total_incentive;
        $kpia_detail->sp_tractor_target=$request->sp_tractor_target;
        $kpia_detail->sp_tractor_actual=$request->sp_tractor_actual;
        $kpia_detail->sp_tractor_weight=$request->sp_tractor_weight;
        $kpia_detail->sp_tractor_score=$request->sp_tractor_score;
        $kpia_detail->sp_tractor_f_score=$request->sp_tractor_f_score;
        $kpia_detail->sp_tractor_base_line=$request->sp_tractor_base_line;
        $kpia_detail->sp_tractor_f_score_total=$request->sp_tractor_f_score_total;
        $kpia_detail->sp_tractor_f_score_percent=$request->sp_tractor_f_score_percent;
        $kpia_detail->sp_tractor_total_incentive=$request->sp_tractor_total_incentive;
        $kpia_detail->sp_nmpt_target=$request->sp_nmpt_target;
        $kpia_detail->sp_nmpt_actual=$request->sp_nmpt_actual;
        $kpia_detail->sp_nmpt_weight=$request->sp_nmpt_weight;
        $kpia_detail->sp_nmpt_score=$request->sp_nmpt_score;
        $kpia_detail->sp_nmpt_f_score=$request->sp_nmpt_f_score;
        $kpia_detail->sp_nmpt_base_line=$request->sp_nmpt_base_line;
        $kpia_detail->sp_nmpt_f_score_total=$request->sp_nmpt_f_score_total;
        $kpia_detail->sp_nmpt_f_score_percent=$request->sp_nmpt_f_score_percent;
        $kpia_detail->sp_nmpt_total_incentive=$request->sp_nmpt_total_incentive;
        $kpia_detail->sp_tractor_plus_nmpt_target=$request->sp_tractor_plus_nmpt_target;
        $kpia_detail->sp_tractor_plus_nmpt_actual=$request->sp_tractor_plus_nmpt_actual;
        $kpia_detail->sp_tractor_plus_nmpt_weight=$request->sp_tractor_plus_nmpt_weight;
        $kpia_detail->sp_tractor_plus_nmpt_score=$request->sp_tractor_plus_nmpt_score;
        $kpia_detail->sp_tractor_plus_nmpt_f_score=$request->sp_tractor_plus_nmpt_f_score;
        $kpia_detail->sp_tractor_plus_nmpt_base_line=$request->sp_tractor_plus_nmpt_base_line;
        $kpia_detail->sp_tractor_plus_nmpt_f_score_total=$request->sp_tractor_plus_nmpt_f_score_total;
        $kpia_detail->sp_tractor_plus_nmpt_f_score_percent=$request->sp_tractor_plus_nmpt_f_score_percent;
        $kpia_detail->sp_tractor_plus_nmpt_total_incentive=$request->sp_tractor_plus_nmpt_total_incentive;
        $kpia_detail->incentive_101_115_mul=$request->incentive_101_115_mul;
        $kpia_detail->incentive_116_140_mul=$request->incentive_116_140_mul;
        $kpia_detail->incentive_141_above_mul=$request->incentive_141_above_mul;
        $kpia_detail->incentive_101_115_amount=$request->incentive_101_115_amount;
        $kpia_detail->incentive_116_140_amount=$request->incentive_116_140_amount;
        $kpia_detail->incentive_141_above_amount=$request->incentive_141_above_amount;
        $kpia_detail->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/kpia_detail");
    }



    public function edit($id){
        
        $kpia=Kpium::all();
        $kpia_detail = KpiaDetail::findOrFail($id);
        return view("kpia_detail.kpia_detail_edit",compact("kpia_detail"))
             ->with("kpia" ,$kpia);;

    }

    public function update(KpiaDetailUpdateRequest $request, $id) {

        $kpia_detail=KpiaDetail::findOrFail($id);
        $kpia_detail->kpia_id=$request->kpia_id;
        $kpia_detail->service_ratio_ws_target=$request->service_ratio_ws_target;
        $kpia_detail->service_ratio_ws_actual=$request->service_ratio_ws_actual;
        $kpia_detail->service_ratio_ws_weight=$request->service_ratio_ws_weight;
        $kpia_detail->service_ratio_ws_score=$request->service_ratio_ws_score;
        $kpia_detail->service_ratio_ws_f_score=$request->service_ratio_ws_f_score;
        $kpia_detail->service_ratio_pws_target=$request->service_ratio_pws_target;
        $kpia_detail->service_ratio_pws_actual=$request->service_ratio_pws_actual;
        $kpia_detail->service_ratio_pws_weight=$request->service_ratio_pws_weight;
        $kpia_detail->service_ratio_pws_score=$request->service_ratio_pws_score;
        $kpia_detail->service_ratio_pws_f_score=$request->service_ratio_pws_f_score;
        $kpia_detail->satisfaction_index_six_target=$request->satisfaction_index_six_target;
        $kpia_detail->satisfaction_index_six_actual=$request->satisfaction_index_six_actual;
        $kpia_detail->satisfaction_index_six_weight=$request->satisfaction_index_six_weight;
        $kpia_detail->satisfaction_index_six_score=$request->satisfaction_index_six_score;
        $kpia_detail->satisfaction_index_six_f_score=$request->satisfaction_index_six_f_score;
        $kpia_detail->satisfaction_index_csi_target=$request->satisfaction_index_csi_target;
        $kpia_detail->satisfaction_index_csi_actual=$request->satisfaction_index_csi_actual;
        $kpia_detail->satisfaction_index_csi_weight=$request->satisfaction_index_csi_weight;
        $kpia_detail->satisfaction_index_csi_score=$request->satisfaction_index_csi_score;
        $kpia_detail->satisfaction_index_csi_f_score=$request->satisfaction_index_csi_f_score;
        $kpia_detail->service_income_target=$request->service_income_target;
        $kpia_detail->service_income_actual=$request->service_income_actual;
        $kpia_detail->service_income_weight=$request->service_income_weight;
        $kpia_detail->service_income_score=$request->service_income_score;
        $kpia_detail->service_income_f_score=$request->service_income_f_score;
        $kpia_detail->report_submission_weight=$request->report_submission_weight;
        $kpia_detail->report_submission_score=$request->report_submission_score;
        $kpia_detail->report_submission_f_score=$request->report_submission_f_score;
        $kpia_detail->app_monitor_weight=$request->app_monitor_weight;
        $kpia_detail->app_monitor_score=$request->app_monitor_score;
        $kpia_detail->app_monitor_f_score=$request->app_monitor_f_score;
        $kpia_detail->team_co_weight=$request->team_co_weight;
        $kpia_detail->team_co_score=$request->team_co_score;
        $kpia_detail->team_co_f_score=$request->team_co_f_score;
        $kpia_detail->service_income_base_line=$request->service_income_base_line;
        $kpia_detail->service_f_score_total=$request->service_f_score_total;
        $kpia_detail->service_f_score_percent=$request->service_f_score_percent;
        $kpia_detail->service_income_total_incentive=$request->service_income_total_incentive;
        $kpia_detail->sp_tractor_target=$request->sp_tractor_target;
        $kpia_detail->sp_tractor_actual=$request->sp_tractor_actual;
        $kpia_detail->sp_tractor_weight=$request->sp_tractor_weight;
        $kpia_detail->sp_tractor_score=$request->sp_tractor_score;
        $kpia_detail->sp_tractor_f_score=$request->sp_tractor_f_score;
        $kpia_detail->sp_tractor_base_line=$request->sp_tractor_base_line;
        $kpia_detail->sp_tractor_f_score_total=$request->sp_tractor_f_score_total;
        $kpia_detail->sp_tractor_f_score_percent=$request->sp_tractor_f_score_percent;
        $kpia_detail->sp_tractor_total_incentive=$request->sp_tractor_total_incentive;
        $kpia_detail->sp_nmpt_target=$request->sp_nmpt_target;
        $kpia_detail->sp_nmpt_actual=$request->sp_nmpt_actual;
        $kpia_detail->sp_nmpt_weight=$request->sp_nmpt_weight;
        $kpia_detail->sp_nmpt_score=$request->sp_nmpt_score;
        $kpia_detail->sp_nmpt_f_score=$request->sp_nmpt_f_score;
        $kpia_detail->sp_nmpt_base_line=$request->sp_nmpt_base_line;
        $kpia_detail->sp_nmpt_f_score_total=$request->sp_nmpt_f_score_total;
        $kpia_detail->sp_nmpt_f_score_percent=$request->sp_nmpt_f_score_percent;
        $kpia_detail->sp_nmpt_total_incentive=$request->sp_nmpt_total_incentive;
        $kpia_detail->sp_tractor_plus_nmpt_target=$request->sp_tractor_plus_nmpt_target;
        $kpia_detail->sp_tractor_plus_nmpt_actual=$request->sp_tractor_plus_nmpt_actual;
        $kpia_detail->sp_tractor_plus_nmpt_weight=$request->sp_tractor_plus_nmpt_weight;
        $kpia_detail->sp_tractor_plus_nmpt_score=$request->sp_tractor_plus_nmpt_score;
        $kpia_detail->sp_tractor_plus_nmpt_f_score=$request->sp_tractor_plus_nmpt_f_score;
        $kpia_detail->sp_tractor_plus_nmpt_base_line=$request->sp_tractor_plus_nmpt_base_line;
        $kpia_detail->sp_tractor_plus_nmpt_f_score_total=$request->sp_tractor_plus_nmpt_f_score_total;
        $kpia_detail->sp_tractor_plus_nmpt_f_score_percent=$request->sp_tractor_plus_nmpt_f_score_percent;
        $kpia_detail->sp_tractor_plus_nmpt_total_incentive=$request->sp_tractor_plus_nmpt_total_incentive;
        $kpia_detail->incentive_101_115_mul=$request->incentive_101_115_mul;
        $kpia_detail->incentive_116_140_mul=$request->incentive_116_140_mul;
        $kpia_detail->incentive_141_above_mul=$request->incentive_141_above_mul;
        $kpia_detail->incentive_101_115_amount=$request->incentive_101_115_amount;
        $kpia_detail->incentive_116_140_amount=$request->incentive_116_140_amount;
        $kpia_detail->incentive_141_above_amount=$request->incentive_141_above_amount;
        $kpia_detail->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/kpia_detail");
    }

    public function show($id){
        $kpia_detail = KpiaDetail::find($id);
        return view("kpia_detail.kpia_detail_show",compact("kpia_detail"));
    }


    public function destroy($id){
        $kpia_detail = KpiaDetail::findOrFail($id);
        $kpia_detail ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/kpia_detail");
    }

}
