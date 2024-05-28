<?php

namespace App\Http\Controllers;

use App\Area;
use App\Company;
use App\District;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DistrictController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
     }
 
 
     public function index(){
         $districts = District::all();
         return view("districts.district_list",compact("districts"));
     }
 
 
     public function create(){
         
        //  $groups=Group::all();
        $areas = Area::all();
         return view("districts.district_create", compact('areas'));
            //   ->with("groups" ,$groups);
     }
 
 
     public function store(Request $request){
 
         $this->validate($request, [
            "name"=>"required|max:100|unique:districts",
            "name_bn"=>"required|max:100|unique:districts",
            "code"=>"required|max:20|unique:districts",
            // "area_id"=>"required|max:20|unique:districts",
            // "group_id"=>"required|numeric|exists:groups,id",
 
         ]);
 
         $district = new District;
         $district->name=$request->name;
         $district->name_bn=$request->name_bn;
         $district->code=$request->code;
         $district->area_id=$request->area_id;
        //  $district->group_id=$request->group_id;
         $district ->save();
         Session::flash("success", "Created Succcessfully !");
         return redirect("/district");
     }
 
 
 
     public function edit($id){
         
        //  $groups=Group::all();
         $district = District::findOrFail($id);
         $areas = Area::all();
         return view("districts.district_edit",compact("district","areas"));
            //   ->with("groups" ,$groups);
 
     }
 
     public function update(Request $request, $id) {
 
         $this->validate($request, [
            "name"=>"required|max:100|unique:districts,name,$id",
            "name_bn"=>"required|max:100|unique:districts,name_bn,$id",
            "code"=>"required|max:20|unique:districts,code,$id",
            // "area_id"=>"required|max:20|unique:districts,area_id,$id",
            // "group_id"=>"required|numeric|exists:groups,id",
 
         ]);
 
         $district = District::findOrFail($id);
         $district->name=$request->name;
         $district->name_bn=$request->name_bn;
         $district->code=$request->code;
         $district->area_id=$request->area_id;
        //  $district->group_id=$request->group_id;
         $district->save();
 
         Session::flash("success", "Edited Succcessfully !");
         return redirect("/district");
     }
 
     public function show($id){
         $district = District::find($id);
         return view("districts.district_show",compact("district"));
     }
 
 
     public function destroy($id){
         $district = District::findOrFail($id);
         $district ->delete();
         Session::flash("success", "Deleted Succcessfully !");
         return redirect("/district");
     }
}
