<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Company;
use App\Group;

class CompanyController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $companies = Company::orderBy('id','Desc')->paginate(20);
        return view("company.company_list",compact("companies"));
    }


    public function create(){
        
        $groups=Group::all();
        return view("company.company_create")
             ->with("groups" ,$groups);;
    }


    public function store(Request $request){

        $this->validate($request, [
           "name"=>"required|max:100|unique:companies",
           "code"=>"required|max:20|unique:companies",
           "group_id"=>"required|numeric|exists:groups,id",

        ]);

        $company = new Company;
        $company->name=$request->name;
        $company->code=$request->code;
        $company->group_id=$request->group_id;
        $company ->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/company");
    }



    public function edit($id){
        
        $groups=Group::all();
        $company = Company::findOrFail($id);
        return view("company.company_edit",compact("company"))
             ->with("groups" ,$groups);;

    }

    public function update(Request $request, $id) {

        $this->validate($request, [
           "name"=>"required|max:100|unique:companies,name,$id",
           "code"=>"required|max:20|unique:companies,code,$id",
           "group_id"=>"required|numeric|exists:groups,id",

        ]);

        $company = Company::findOrFail($id);
        $company->name=$request->name;
        $company->code=$request->code;
        $company->group_id=$request->group_id;
        $company->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/company");
    }

    public function show($id){
        $company = Company::find($id);
        return view("company.company_show",compact("company"));
    }


    public function destroy($id){
        $company = Company::findOrFail($id);
        $company ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/company");
    }

}
