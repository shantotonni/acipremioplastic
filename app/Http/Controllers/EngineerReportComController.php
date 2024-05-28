<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\EngineerReportCom;
use App\User;
use App\Http\Requests\EngineerReportComStoreRequest;
use App\Http\Requests\EngineerReportComUpdateRequest;


class EngineerReportComController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(Request $request){
        if($request->date){
            $date = date('Y-m-d',strtotime($request->date));
          }else{
            $date = date('Y-m-d');
          }
       
        $engineer_report_coms = EngineerReportCom::orderBy('id','Desc')
                                ->whereMonth('date',date('m',strtotime($date)))
                                ->whereYear('date',date('Y',strtotime($date)))
                                ->get();
        return view("engineer_report_com.engineer_report_com_list",compact("engineer_report_coms"));
    }


    public function create(){
        
        $users=User::where("kpi_type_id",1)->get();
        return view("engineer_report_com.engineer_report_com_create")
             ->with("users" ,$users);;
    }


    public function store(EngineerReportComStoreRequest $request){

        $engineer_report_com= new EngineerReportCom;
        $engineer_report_com->date=date("Y-m-d",strtotime($request->date));
        $engineer_report_com->user_id=$request->user_id;
        $engineer_report_com->report_actual=$request->report_actual;
        $engineer_report_com->app_dashboard_actual=$request->app_dashboard_actual;
        $engineer_report_com->team_coordination_actual=$request->team_coordination_actual;
        $engineer_report_com->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/engineer_report_com");
    }



    public function edit($id){
        
        $users=User::where("kpi_type_id",1)->get();
        $engineer_report_com = EngineerReportCom::findOrFail($id);
        return view("engineer_report_com.engineer_report_com_edit",compact("engineer_report_com"))
             ->with("users" ,$users);;

    }

    public function update(EngineerReportComUpdateRequest $request, $id) {

        $engineer_report_com=EngineerReportCom::findOrFail($id);
        $engineer_report_com->date=date("Y-m-d",strtotime($request->date));
        $engineer_report_com->user_id=$request->user_id;
        $engineer_report_com->report_actual=$request->report_actual;
        $engineer_report_com->app_dashboard_actual=$request->app_dashboard_actual;
        $engineer_report_com->team_coordination_actual=$request->team_coordination_actual;
        $engineer_report_com->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/engineer_report_com");
    }

    public function show($id){
        $engineer_report_com = EngineerReportCom::find($id);
        return view("engineer_report_com.engineer_report_com_show",compact("engineer_report_com"));
    }


    public function destroy($id){
        $engineer_report_com = EngineerReportCom::findOrFail($id);
        $engineer_report_com ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/engineer_report_com");
    }

}
