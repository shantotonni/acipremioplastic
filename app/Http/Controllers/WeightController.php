<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Weight;
use App\KpiType;
use App\Http\Requests\WeightStoreRequest;
use App\Http\Requests\WeightUpdateRequest;


class WeightController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $weights = Weight::orderBy('id','Desc')->paginate(20);
        return view("weight.weight_list",compact("weights"));
    }


    public function create(){
        
        $kpi_types=KpiType::all();
        return view("weight.weight_create")
             ->with("kpi_types" ,$kpi_types);;
    }


    public function store(WeightStoreRequest $request){

        $weight=Weight::create($request->all());
        Session::flash("success", "Created Succcessfully !");
        return redirect("/weight");
    }



    public function edit($id){
        
        $kpi_types=KpiType::all();
        $weight = Weight::findOrFail($id);
        return view("weight.weight_edit",compact("weight"))
             ->with("kpi_types" ,$kpi_types);;

    }

    public function update(WeightUpdateRequest $request, $id) {

        $weight=Weight::find($id)->update($request->all());
        Session::flash("success", "Edited Succcessfully !");
        return redirect("/weight");
    }

    public function show($id){
        $weight = Weight::find($id);
        return view("weight.weight_show",compact("weight"));
    }


    public function destroy($id){
        $weight = Weight::findOrFail($id);
        $weight ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/weight");
    }

}
