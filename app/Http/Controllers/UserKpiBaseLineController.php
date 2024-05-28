<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\UserKpiBaseLine;
use App\UserKpi;
use App\KpiGroup;
use App\Http\Requests\UserKpiBaseLineStoreRequest;
use App\Http\Requests\UserKpiBaseLineUpdateRequest;


class UserKpiBaseLineController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $user_kpi_base_lines = UserKpiBaseLine::orderBy('id','Desc')->paginate(20);
        return view("user_kpi_base_line.user_kpi_base_line_list",compact("user_kpi_base_lines"));
    }


    public function create(){
        
        $user_kpis=UserKpi::all();
        $kpi_groups=KpiGroup::all();
        return view("user_kpi_base_line.user_kpi_base_line_create")
             ->with("user_kpis" ,$user_kpis)
             ->with("kpi_groups" ,$kpi_groups);;
    }


    public function store(UserKpiBaseLineStoreRequest $request){

        $user_kpi_base_line= new UserKpiBaseLine;
        $user_kpi_base_line->user_kpi_id=$request->user_kpi_id;
        $user_kpi_base_line->kpi_group_id=$request->kpi_group_id;
        $user_kpi_base_line->amount=$request->amount;
        $user_kpi_base_line->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/user_kpi_base_line");
    }



    public function edit($id){
        
        $user_kpis=UserKpi::all();
        $kpi_groups=KpiGroup::all();
        $user_kpi_base_line = UserKpiBaseLine::findOrFail($id);
        return view("user_kpi_base_line.user_kpi_base_line_edit",compact("user_kpi_base_line"))
             ->with("user_kpis" ,$user_kpis)
             ->with("kpi_groups" ,$kpi_groups);;

    }

    public function update(UserKpiBaseLineUpdateRequest $request, $id) {

        $user_kpi_base_line=UserKpiBaseLine::findOrFail($id);
        $user_kpi_base_line->user_kpi_id=$request->user_kpi_id;
        $user_kpi_base_line->kpi_group_id=$request->kpi_group_id;
        $user_kpi_base_line->amount=$request->amount;
        $user_kpi_base_line->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/user_kpi_base_line");
    }

    public function show($id){
        $user_kpi_base_line = UserKpiBaseLine::find($id);
        return view("user_kpi_base_line.user_kpi_base_line_show",compact("user_kpi_base_line"));
    }


    public function destroy($id){
        $user_kpi_base_line = UserKpiBaseLine::findOrFail($id);
        $user_kpi_base_line ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/user_kpi_base_line");
    }

}
