<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Designation;

class DesignationController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $designations = Designation::orderBy('id','Desc')->paginate(20);
        return view("designation.designation_list",compact("designations"));
    }


    public function create(){
        
        return view("designation.designation_create");;
    }


    public function store(Request $request){

        $this->validate($request, [
           "name"=>"required|max:50|unique:designations",
           "code"=>"required|max:50|unique:designations",

        ]);

        $designation = new Designation;
        $designation->name=$request->name;
        $designation->code=$request->code;
        $designation->service_base_amount=$request->service_base_amount;
        $designation->tractor_parts_base_amount=$request->tractor_parts_base_amount;
        $designation->nm_parts_base_amount=$request->nm_parts_base_amount;
        $designation ->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/designation");
    }



    public function edit($id){
        
        $designation = Designation::find($id);
        return view("designation.designation_edit",compact("designation"));;

    }

    public function update(Request $request, $id) {

        $this->validate($request, [
           "name"=>"required|max:50|unique:designations,name,$id",
           "code"=>"required|max:50|unique:designations,code,$id",

        ]);

        $designation = Designation::find($id);
        $designation->name=$request->name;
        $designation->code=$request->code;
        $designation->service_base_amount=$request->service_base_amount;
        $designation->tractor_parts_base_amount=$request->tractor_parts_base_amount;
        $designation->nm_parts_base_amount=$request->nm_parts_base_amount;
        $designation->save();

        Session::flash("success", "Edited Successfully !");
        return redirect("/designation");
    }

    public function show($id){
        $designation = Designation::find($id);
        return view("designation.designation_show",compact("designation"));
    }


    public function destroy($id){
        $designation = Designation::findOrFail($id);
        $designation ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/designation");
    }

}
