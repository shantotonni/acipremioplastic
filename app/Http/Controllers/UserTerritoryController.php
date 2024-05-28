<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\UserTerritory;
use App\Territory;
use App\User;
use App\Http\Requests\UserTerritoryStoreRequest;
use App\Http\Requests\UserTerritoryUpdateRequest;


class UserTerritoryController extends Controller{

    public function __construct(){
       $this->middleware('auth');
    }

    public function index(){
        $user_territories = UserTerritory::orderBy('id','Desc')->get();
        return view("user_territory.user_territory_list",compact("user_territories"));
    }

    public function create(){
        
        $territories = Territory::all();
        $users=User::whereIn('role_id',['3','7','8'])->get();
        return view("user_territory.user_territory_create")
             ->with("territories" ,$territories)
             ->with("users" ,$users);;
    }

    public function store(UserTerritoryStoreRequest $request){
        if($request->user_id == $request->supervisor_id){
            Session::flash("danger", "Supervisor & Technician Can not be same");
            return redirect()->back();
        }
        $user_territory= new UserTerritory;
        $user_territory->territory_id = $request->territory_id;
        $user_territory->supervisor_id = $request->supervisor_id;
        $user_territory->user_id = $request->user_id;
        $user_territory->save();
        Session::flash("success", "Created Succcessfully !");
        return redirect("/user_territory");
    }

    public function edit($id){
        $territories=Territory::all();
        $users=User::where('role_id',3)->get();
        $user_territory = UserTerritory::findOrFail($id);
        return view("user_territory.user_territory_edit",compact("user_territory"))
             ->with("territories" ,$territories)
             ->with("users" ,$users);;
    }

    public function update(UserTerritoryUpdateRequest $request, $id) {

        if($request->user_id == $request->supervisor_id){
            Session::flash("danger", "Supervisor & Technician Can not be same");
            return redirect()->back();
        }
        $user_territory= UserTerritory::find($id);
        $user_territory->territory_id  = $request->territory_id;
        $user_territory->supervisor_id = $request->supervisor_id;
        $user_territory->user_id = $request->user_id;
        $user_territory->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/user_territory");
    }

    public function show($id){
        $user_territory = UserTerritory::find($id);
        return view("user_territory.user_territory_show",compact("user_territory"));
    }


    public function destroy($id){
        $user_territory = UserTerritory::findOrFail($id);
        $user_territory ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/user_territory");
    }

}
