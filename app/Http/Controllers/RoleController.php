<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Role;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;


class RoleController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $roles = Role::orderBy('id','Desc')->paginate(20);
        return view("role.role_list",compact("roles"));
    }


    public function create(){
        
        return view("role.role_create");;
    }


    public function store(RoleStoreRequest $request){

        $role=Role::create($request->all());
        Session::flash("success", "Created Succcessfully !");
        return redirect("/role");
    }



    public function edit($id){
        
        $role = Role::findOrFail($id);
        return view("role.role_edit",compact("role"));;

    }

    public function update(RoleUpdateRequest $request, $id) {

        $role=Role::find($id)->update($request->all());
        Session::flash("success", "Edited Succcessfully !");
        return redirect("/role");
    }

    public function show($id){
        $role = Role::find($id);
        return view("role.role_show",compact("role"));
    }


    public function destroy($id){
        $role = Role::findOrFail($id);
        $role ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/role");
    }

}
