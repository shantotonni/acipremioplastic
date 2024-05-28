<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\BaseLine;
use App\KpiType;
use App\Http\Requests\BaseLineStoreRequest;
use App\Http\Requests\BaseLineUpdateRequest;


class BaseLineController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $base_lines = BaseLine::orderBy('id','Desc')->paginate(20);
        return view("base_line.base_line_list",compact("base_lines"));
    }


    public function create(){
        
        $kpi_types=KpiType::all();
        return view("base_line.base_line_create")
             ->with("kpi_types" ,$kpi_types);;
    }


    public function store(BaseLineStoreRequest $request){

        $base_line=BaseLine::create($request->all());
        Session::flash("success", "Created Succcessfully !");
        return redirect("/base_line");
    }



    public function edit($id){
        
        $kpi_types=KpiType::all();
        $base_line = BaseLine::findOrFail($id);
        return view("base_line.base_line_edit",compact("base_line"))
             ->with("kpi_types" ,$kpi_types);;

    }

    public function update(BaseLineUpdateRequest $request, $id) {

        $base_line=BaseLine::find($id)->update($request->all());
        Session::flash("success", "Edited Succcessfully !");
        return redirect("/base_line");
    }

    public function show($id){
        $base_line = BaseLine::find($id);
        return view("base_line.base_line_show",compact("base_line"));
    }


    public function destroy($id){
        $base_line = BaseLine::findOrFail($id);
        $base_line ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/base_line");
    }

}
