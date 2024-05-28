<?php

namespace App\Http\Controllers;

use App\District;
use App\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;

class UpazilaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
     }
 
 
     public function index(){
         return view("upazila.upazila_list");
     }

     public function upazilaData()
     {
         $upazilas = Upazila::all();
         return Datatables::of($upazilas)
         ->addColumn('action', function ($upazila) {
            return '<a href="/motor-service/upazila/'.$upazila->id.'/edit" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>'
                .'&nbsp;&nbsp;<a id="openDeleteModal" data-toggle="modal" data-id="'.$upazila->id.'" title="Delete"  href=""><button type="button" class="btn btn-xs btn-danger btn-flat">Del</button></a>';  
        })
            ->make(true);
     }
 
 
     public function create(){
         
        //  $groups=Group::all();
        $districts = District::all();
         return view("upazila.upazila_create", compact('districts'));
            //   ->with("groups" ,$groups);
     }
 
 
     public function store(Request $request){
 
         $this->validate($request, [
            "name"=>"required|max:100",
            "name_bn"=>"required|max:100",
            "code"=>"required|max:100|unique:upazilas"
 
         ]);
 
         $upazila = new Upazila;
         $upazila->name=$request->name;
         $upazila->name_bn=$request->name_bn;
         $upazila->code=$request->code;
         $upazila->district_id=$request->district_id;
        //  $district->group_id=$request->group_id;
         $upazila ->save();
         Session::flash("success", "Created Succcessfully !");
         return redirect("/upazila");
     }
 
 
 
     public function edit($id){
         
        //  $groups=Group::all();
         $upazila = Upazila::findOrFail($id);
         $districts = District::all();
         return view("upazila.upazila_edit",compact("upazila","districts"));
            //   ->with("groups" ,$groups);
 
     }
 
     public function update(Request $request, $id) {
 
         $this->validate($request, [
            "name"=>"required|max:100",
            "name_bn"=>"required|max:100",
            "code"=>"required|max:100|unique:upazilas,code,$id"
         ]);
 
         $upazila = Upazila::findOrFail($id);
         $upazila->district_id=$request->district_id;
         $upazila->name=$request->name;
         $upazila->name_bn=$request->name_bn;
         $upazila->code=$request->code;
         $upazila->MotorsMSRCode=$request->MotorsMSRCode;
         $upazila->save();
 
         Session::flash("success", "Edited Succcessfully !");
         return redirect("/upazila");
     }
 
     public function show($id){
         $upazila = Upazila::find($id);
         return view("upazila.upazila_show",compact("upazila"));
     }
 
 
     public function destroy($id){
         $upazila = Upazila::findOrFail($id);
         $upazila ->delete();
         Session::flash("success", "Deleted Succcessfully !");
         return redirect("/upazila");
     }
}
