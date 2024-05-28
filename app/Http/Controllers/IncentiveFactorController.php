<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\IncentiveFactor;
use App\KpiType;
use App\Http\Requests\IncentiveFactorStoreRequest;
use App\Http\Requests\IncentiveFactorUpdateRequest;


class IncentiveFactorController extends Controller{

    public function __construct(){
       $this->middleware('auth');
    }

    public function index(){
        $incentive_factors = IncentiveFactor::orderBy('id','Desc')->paginate(20);
        return view("incentive_factor.incentive_factor_list",compact("incentive_factors"));
    }

    public function create(){
        
        $kpi_types=KpiType::all();
        return view("incentive_factor.incentive_factor_create")
             ->with("kpi_types" ,$kpi_types);;
    }

    public function store(IncentiveFactorStoreRequest $request){

        $incentive_factor=IncentiveFactor::create($request->all());
        Session::flash("success", "Created Succcessfully !");
        return redirect("/incentive_factor");
    }

    public function edit($id){
        
        $kpi_types=KpiType::all();
        $incentive_factor = IncentiveFactor::findOrFail($id);
        return view("incentive_factor.incentive_factor_edit",compact("incentive_factor"))
             ->with("kpi_types" ,$kpi_types);;

    }

    public function update(IncentiveFactorUpdateRequest $request, $id) {

        $incentive_factor=IncentiveFactor::find($id)->update($request->all());
        Session::flash("success", "Edited Succcessfully !");
        return redirect("/incentive_factor");
    }

    public function show($id){
        $incentive_factor = IncentiveFactor::find($id);
        return view("incentive_factor.incentive_factor_show",compact("incentive_factor"));
    }

    public function destroy($id){
        $incentive_factor = IncentiveFactor::findOrFail($id);
        $incentive_factor ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/incentive_factor");
    }

}
