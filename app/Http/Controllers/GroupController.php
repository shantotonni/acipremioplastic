<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Group;

class GroupController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $groups = Group::orderBy('id','Desc')->paginate(20);
        return view("group.group_list",compact("groups"));
    }


    public function create(){
        
        return view("group.group_create");;
    }


    public function store(Request $request){

        $this->validate($request, [
           "name"=>"required|max:100|unique:groups",
           "code"=>"required|max:20|unique:groups",

        ]);

        $group = new Group;
        $group->name=$request->name;
        $group->code=$request->code;
        $group ->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/group");
    }



    public function edit($id){
        
        $group = Group::findOrFail($id);
        return view("group.group_edit",compact("group"));;

    }

    public function update(Request $request, $id) {

        $this->validate($request, [
           "name"=>"required|max:100|unique:groups,name,$id",
           "code"=>"required|max:20|unique:groups,code,$id",

        ]);

        $group = Group::findOrFail($id);
        $group->name=$request->name;
        $group->code=$request->code;
        $group->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/group");
    }

    public function show($id){
        $group = Group::find($id);
        return view("group.group_show",compact("group"));
    }


    public function destroy($id){
        $group = Group::findOrFail($id);
        $group ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/group");
    }

}
