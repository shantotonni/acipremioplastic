<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\KpiGroup;
use App\Http\Requests\KpiGroupStoreRequest;
use App\Http\Requests\KpiGroupUpdateRequest;


class KpiGroupController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $kpi_groups = KpiGroup::orderBy('id','Desc')->paginate(20);
        return view("kpi_group.kpi_group_list",compact("kpi_groups"));
    }


    public function create(){
        
        return view("kpi_group.kpi_group_create");;
    }


    public function store(KpiGroupStoreRequest $request){

        $kpi_group=KpiGroup::create($request->all());
        Session::flash("success", "Created Succcessfully !");
        return redirect("/kpi_group");
    }



    public function edit($id){
        
        $kpi_group = KpiGroup::findOrFail($id);
        return view("kpi_group.kpi_group_edit",compact("kpi_group"));;

    }

    public function update(KpiGroupUpdateRequest $request, $id) {

        $kpi_group=KpiGroup::find($id)->update($request->all());
        Session::flash("success", "Edited Succcessfully !");
        return redirect("/kpi_group");
    }

    public function show($id){
        $kpi_group = KpiGroup::find($id);
        return view("kpi_group.kpi_group_show",compact("kpi_group"));
    }


    public function destroy($id){
        $kpi_group = KpiGroup::findOrFail($id);
        $kpi_group ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/kpi_group");
    }

}
