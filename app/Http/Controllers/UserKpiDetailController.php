<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\UserKpiDetail;
use App\UserKpi;
use App\KpiTopic;
use App\Http\Requests\UserKpiDetailStoreRequest;
use App\Http\Requests\UserKpiDetailUpdateRequest;


class UserKpiDetailController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $user_kpi_details = UserKpiDetail::orderBy('id','Desc')->paginate(20);
        return view("user_kpi_detail.user_kpi_detail_list",compact("user_kpi_details"));
    }


    public function create(){
        
        $user_kpis=UserKpi::all();
        $kpi_topics=KpiTopic::all();
        return view("user_kpi_detail.user_kpi_detail_create")
             ->with("user_kpis" ,$user_kpis)
             ->with("kpi_topics" ,$kpi_topics);;
    }


    public function store(UserKpiDetailStoreRequest $request){

        $user_kpi_detail= new UserKpiDetail;
        $user_kpi_detail->user_kpi_id=$request->user_kpi_id;
        $user_kpi_detail->kpi_topic_id=$request->kpi_topic_id;
        $user_kpi_detail->target=$request->target;
        $user_kpi_detail->actual=$request->actual;
        $user_kpi_detail->weight=$request->weight;
        $user_kpi_detail->score=$request->score;
        $user_kpi_detail->f_score=$request->f_score;
        $user_kpi_detail->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/user_kpi_detail");
    }



    public function edit($id){
        
        $user_kpis=UserKpi::all();
        $kpi_topics=KpiTopic::all();
        $user_kpi_detail = UserKpiDetail::findOrFail($id);
        return view("user_kpi_detail.user_kpi_detail_edit",compact("user_kpi_detail"))
             ->with("user_kpis" ,$user_kpis)
             ->with("kpi_topics" ,$kpi_topics);;

    }

    public function update(UserKpiDetailUpdateRequest $request, $id) {

        $user_kpi_detail=UserKpiDetail::findOrFail($id);
        $user_kpi_detail->user_kpi_id=$request->user_kpi_id;
        $user_kpi_detail->kpi_topic_id=$request->kpi_topic_id;
        $user_kpi_detail->target=$request->target;
        $user_kpi_detail->actual=$request->actual;
        $user_kpi_detail->weight=$request->weight;
        $user_kpi_detail->score=$request->score;
        $user_kpi_detail->f_score=$request->f_score;
        $user_kpi_detail->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/user_kpi_detail");
    }

    public function show($id){
        $user_kpi_detail = UserKpiDetail::find($id);
        return view("user_kpi_detail.user_kpi_detail_show",compact("user_kpi_detail"));
    }


    public function destroy($id){
        $user_kpi_detail = UserKpiDetail::findOrFail($id);
        $user_kpi_detail ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/user_kpi_detail");
    }

}
