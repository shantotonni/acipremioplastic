<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Department;
use App\BusinessUnit;

class DepartmentController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $departments = Department::orderBy('id','Desc')->paginate(20);
        return view("department.department_list",compact("departments"));
    }


    public function create(){
        
        $business_units=BusinessUnit::all();
        return view("department.department_create")
             ->with("business_units" ,$business_units);;
    }


    public function store(Request $request){

        $this->validate($request, [
           "business_unit_id"=>"required|numeric|exists:business_units,id",
           "name"=>"required|max:191|unique:departments",
           "code"=>"required|max:191|unique:departments",

        ]);

        $department = new Department;
        $department->business_unit_id=$request->business_unit_id;
        $department->name=$request->name;
        $department->code=$request->code;
        $department ->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/department");
    }



    public function edit($id){
        
        $business_units=BusinessUnit::all();
        $department = Department::find($id);
        return view("department.department_edit",compact("department"))
             ->with("business_units" ,$business_units);;

    }

    public function update(Request $request, $id) {

        $this->validate($request, [
           "business_unit_id"=>"required|numeric|exists:business_units,id",
           "name"=>"required|max:191|unique:departments,name,$id",
           "code"=>"required|max:191|unique:departments,code,$id",

        ]);

        $department = Department::find($id);
        $department->business_unit_id=$request->business_unit_id;
        $department->name=$request->name;
        $department->code=$request->code;
        $department->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/department");
    }

    public function show($id){
        $department = Department::find($id);
        return view("department.department_show",compact("department"));
    }


    public function destroy($id){
        $department = Department::findOrFail($id);
        $department ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/department");
    }

}
