<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\ServiceMaster;
use App\Http\Requests\ServiceMasterStoreRequest;
use App\Http\Requests\ServiceMasterUpdateRequest;


class ServiceMasterController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $service_masters = ServiceMaster::orderBy('id','Desc')->paginate(20);
        return view("service_master.service_master_list",compact("service_masters"));
    }


    public function create(){
        
        return view("service_master.service_master_create");;
    }


    public function store(ServiceMasterStoreRequest $request){

        $service_master= new ServiceMaster;
        $service_master->name=$request->name;
        $service_master->code=$request->code;
        $service_master->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/service_master");
    }



    public function edit($id){
        
        $service_master = ServiceMaster::findOrFail($id);
        return view("service_master.service_master_edit",compact("service_master"));;

    }

    public function update(ServiceMasterUpdateRequest $request, $id) {

        $service_master=ServiceMaster::findOrFail($id);
        $service_master->name=$request->name;
        $service_master->code=$request->code;
        $service_master->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/service_master");
    }

    public function show($id){
        $service_master = ServiceMaster::find($id);
        return view("service_master.service_master_show",compact("service_master"));
    }


    public function destroy($id){
        $service_master = ServiceMaster::findOrFail($id);
        $service_master ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/service_master");
    }

}
