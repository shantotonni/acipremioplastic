<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\KpiType;
use App\Http\Requests\KpiTypeStoreRequest;
use App\Http\Requests\KpiTypeUpdateRequest;


class KpiTypeController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $kpi_types = KpiType::orderBy('id','Desc')->paginate(20);
        return view("kpi_type.kpi_type_list",compact("kpi_types"));
    }


    public function create(){
        
        return view("kpi_type.kpi_type_create");;
    }


    public function store(KpiTypeStoreRequest $request){

        $kpi_type=KpiType::create($request->all());
        Session::flash("success", "Created Succcessfully !");
        return redirect("/kpi_type");
    }



    public function edit($id){
        
        $kpi_type = KpiType::findOrFail($id);
        return view("kpi_type.kpi_type_edit",compact("kpi_type"));;

    }

    public function update(KpiTypeUpdateRequest $request, $id) {

        $kpi_type=KpiType::find($id)->update($request->all());
        Session::flash("success", "Edited Succcessfully !");
        return redirect("/kpi_type");
    }

    public function show($id){
        $kpi_type = KpiType::find($id);
        return view("kpi_type.kpi_type_show",compact("kpi_type"));
    }


    public function destroy($id){
        $kpi_type = KpiType::findOrFail($id);
        $kpi_type ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/kpi_type");
    }

}
