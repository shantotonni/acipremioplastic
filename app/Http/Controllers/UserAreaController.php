<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\UserArea;
use App\Area;
use App\User;
use App\Http\Requests\UserAreaStoreRequest;
use App\Http\Requests\UserAreaUpdateRequest;


class UserAreaController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $user_areas = UserArea::orderBy('id','Desc')->get();
        return view("user_area.user_area_list",compact("user_areas"));
    }


    public function create(){
        
        $areas=Area::all();
        $users=User::where('role_id',2)->get();
        return view("user_area.user_area_create")
             ->with("areas" ,$areas)
             ->with("users" ,$users);;
    }


    public function store(UserAreaStoreRequest $request){
        $user_area=UserArea::create($request->all());
        Session::flash("success", "Created Succcessfully !");
        return redirect("/user_area");
    }



    public function edit($id){
        
        $areas=Area::all();
        $users=User::where('role_id',2)->get();
        $user_area = UserArea::findOrFail($id);
        return view("user_area.user_area_edit",compact("user_area"))
             ->with("areas" ,$areas)
             ->with("users" ,$users);;

    }

    public function update(UserAreaUpdateRequest $request, $id) {

        $user_area=UserArea::find($id)->update($request->all());
        Session::flash("success", "Edited Succcessfully !");
        return redirect("/user_area");
    }

    public function show($id){
        $user_area = UserArea::find($id);
        return view("user_area.user_area_show",compact("user_area"));
    }


    public function destroy($id){
        $user_area = UserArea::findOrFail($id);
        $user_area ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/user_area");
    }

}
