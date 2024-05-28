<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\UserKpi;
use App\User;
use App\KpiType;
use App\Http\Requests\UserKpiStoreRequest;
use App\Http\Requests\UserKpiUpdateRequest;


class UserKpiController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $user_kpis = UserKpi::orderBy('id','Desc')->paginate(20);
        return view("user_kpi.user_kpi_list",compact("user_kpis"));
    }


    public function create(){
        
        $users=User::all();
        $kpi_types=KpiType::all();
        return view("user_kpi.user_kpi_create")
             ->with("users" ,$users)
             ->with("kpi_types" ,$kpi_types);;
    }


    public function store(UserKpiStoreRequest $request){

        $user_kpi= new UserKpi;
        $user_kpi->date=date("Y-m-d",strtotime($request->date));
        $user_kpi->user_id=$request->user_id;
        $user_kpi->kpi_type_id=$request->kpi_type_id;
        $user_kpi->total_kpi_target=$request->total_kpi_target;
        $user_kpi->total_kpi_ach=$request->total_kpi_ach;
        $user_kpi->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/user_kpi");
    }



    public function edit($id){
        
        $users=User::all();
        $kpi_types=KpiType::all();
        $user_kpi = UserKpi::findOrFail($id);
        return view("user_kpi.user_kpi_edit",compact("user_kpi"))
             ->with("users" ,$users)
             ->with("kpi_types" ,$kpi_types);;

    }

    public function update(UserKpiUpdateRequest $request, $id) {

        $user_kpi=UserKpi::findOrFail($id);
        $user_kpi->date=date("Y-m-d",strtotime($request->date));
        $user_kpi->user_id=$request->user_id;
        $user_kpi->kpi_type_id=$request->kpi_type_id;
        $user_kpi->total_kpi_target=$request->total_kpi_target;
        $user_kpi->total_kpi_ach=$request->total_kpi_ach;
        $user_kpi->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/user_kpi");
    }

    public function show($id){
        $user_kpi = UserKpi::find($id);
        return view("user_kpi.user_kpi_show",compact("user_kpi"));
    }


    public function destroy($id){
        $user_kpi = UserKpi::findOrFail($id);
        $user_kpi ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/user_kpi");
    }

}
