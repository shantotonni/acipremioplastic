<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\ServiceType;
use App\Http\Requests\ServiceTypeStoreRequest;
use App\Http\Requests\ServiceTypeUpdateRequest;


class ServiceTypeController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $service_types = ServiceType::orderBy('id','Desc')->paginate(20);
        return view("service_type.service_type_list",compact("service_types"));
    }


    public function create(){
        
        return view("service_type.service_type_create");;
    }


    public function store(ServiceTypeStoreRequest $request){

        $service_type= new ServiceType;
        $service_type->name=$request->name;
        $service_type->name_bn=$request->name_bn;
        $service_type->code=$request->code;
        $service_type->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/service_type");
    }



    public function edit($id){
        
        $service_type = ServiceType::findOrFail($id);
        return view("service_type.service_type_edit",compact("service_type"));;

    }

    public function update(ServiceTypeUpdateRequest $request, $id) {

        $service_type=ServiceType::findOrFail($id);
        $service_type->name=$request->name;
        $service_type->name_bn=$request->name_bn;
        $service_type->code=$request->code;
        $service_type->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/service_type");
    }

    public function show($id){
        $service_type = ServiceType::find($id);
        return view("service_type.service_type_show",compact("service_type"));
    }


    public function destroy($id){
        $service_type = ServiceType::findOrFail($id);
        $service_type ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/service_type");
    }

}
