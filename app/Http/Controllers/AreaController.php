<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Area;
use App\Http\Requests\AreaStoreRequest;
use App\Http\Requests\AreaUpdateRequest;


class AreaController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $areas = Area::orderBy('id','Desc')->get();
        return view("area.area_list",compact("areas"));
    }


    public function create(){
        
        return view("area.area_create");;
    }


    public function store(AreaStoreRequest $request){

        $area= new Area;
        $area->name=$request->name;
        $area->name_bn=$request->name_bn;
        $area->code=$request->code;
        $area->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/area");
    }



    public function edit($id){
        
        $area = Area::findOrFail($id);
        return view("area.area_edit",compact("area"));;

    }

    public function update(AreaUpdateRequest $request, $id) {

        $area=Area::findOrFail($id);
        $area->name=$request->name;
        $area->name_bn=$request->name_bn;
        $area->code=$request->code;
        $area->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/area");
    }

    public function show($id){
        $area = Area::find($id);
        return view("area.area_show",compact("area"));
    }


    public function destroy($id){
        $area = Area::findOrFail($id);
        $area ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/area");
    }

}
