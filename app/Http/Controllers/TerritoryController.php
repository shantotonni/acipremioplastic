<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Territory;
use App\Area;
use App\Http\Requests\TerritoryStoreRequest;
use App\Http\Requests\TerritoryUpdateRequest;


class TerritoryController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $territories = Territory::orderBy('area_id','Asc')->get();
        return view("territory.territory_list",compact("territories"));
    }


    public function create(){
        
        $areas=Area::all();
        return view("territory.territory_create")
             ->with("areas" ,$areas);;
    }


    public function store(TerritoryStoreRequest $request){

        $territory=Territory::create($request->all());
        Session::flash("success", "Created Succcessfully !");
        return redirect("/territory");
    }



    public function edit($id){
        
        $areas=Area::all();
        $territory = Territory::findOrFail($id);
        return view("territory.territory_edit",compact("territory"))
             ->with("areas" ,$areas);;

    }

    public function update(TerritoryUpdateRequest $request, $id) {

        $territory=Territory::find($id)->update($request->all());
        Session::flash("success", "Edited Succcessfully !");
        return redirect("/territory");
    }

    public function show($id){
        $territory = Territory::find($id);
        return view("territory.territory_show",compact("territory"));
    }


    public function destroy($id){
        $territory = Territory::findOrFail($id);
        $territory ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/territory");
    }

}
