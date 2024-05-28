<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\UserKpiCode;
use App\User;
use App\Http\Requests\UserKpiCodeStoreRequest;
use App\Http\Requests\UserKpiCodeUpdateRequest;


class UserKpiCodeController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $user_kpi_codes = UserKpiCode::all();
        return view("user_kpi_code.user_kpi_code_list",compact("user_kpi_codes"));
    }


    public function create(){
        $users=User::all();
        return view("user_kpi_code.user_kpi_code_create")
             ->with("users" ,$users);;
    }


    public function store(UserKpiCodeStoreRequest $request){

        $user_kpi_code= new UserKpiCode;
        $user_kpi_code->user_id=$request->user_id;
        $user_kpi_code->service_income_code=$request->service_income_code;
        $user_kpi_code->tractor_spare_parts_code=$request->tractor_spare_parts_code;
        $user_kpi_code->tractor_sonalika_lub_code=$request->tractor_sonalika_lub_code;
        $user_kpi_code->tractor_power_oil_code=$request->tractor_power_oil_code;
        $user_kpi_code->nm_spare_parts_code=$request->nm_spare_parts_code;
        $user_kpi_code->nm_power_oil_code=$request->nm_power_oil_code;
        $user_kpi_code->pt_spare_parts_code=$request->pt_spare_parts_code;
        $user_kpi_code->pt_power_oil_code=$request->pt_power_oil_code;
        $user_kpi_code->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/user_kpi_code");
    }



    public function edit($id){
        
        $users=User::all();
        $user_kpi_code = UserKpiCode::findOrFail($id);
        return view("user_kpi_code.user_kpi_code_edit",compact("user_kpi_code"))
             ->with("users" ,$users);;

    }

    public function update(UserKpiCodeUpdateRequest $request, $id) {

        $user_kpi_code=UserKpiCode::findOrFail($id);
        $user_kpi_code->user_id=$request->user_id;
        $user_kpi_code->service_income_code=$request->service_income_code;
        $user_kpi_code->tractor_spare_parts_code=$request->tractor_spare_parts_code;
        $user_kpi_code->tractor_sonalika_lub_code=$request->tractor_sonalika_lub_code;
        $user_kpi_code->tractor_power_oil_code=$request->tractor_power_oil_code;
        $user_kpi_code->nm_spare_parts_code=$request->nm_spare_parts_code;
        $user_kpi_code->nm_power_oil_code=$request->nm_power_oil_code;
        $user_kpi_code->pt_spare_parts_code=$request->pt_spare_parts_code;
        $user_kpi_code->pt_power_oil_code=$request->pt_power_oil_code;
        $user_kpi_code->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/user_kpi_code");
    }

    public function show($id){
        $user_kpi_code = UserKpiCode::find($id);
        return view("user_kpi_code.user_kpi_code_show",compact("user_kpi_code"));
    }


    public function destroy($id){
        $user_kpi_code = UserKpiCode::findOrFail($id);
        $user_kpi_code ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/user_kpi_code");
    }

}
