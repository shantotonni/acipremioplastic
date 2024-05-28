<?php

namespace App\Http\Controllers;

use App\Area;
use App\SalesManagerInfo;
use App\ServiceManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ServiceManagerController extends Controller
{
    public function index()
    {
        $serviceManagers = ServiceManager::orderBy('id','desc')->paginate(10);
        return view('service_manager.list',compact('serviceManagers'));
    }

    public function create()
    {
        $areas = Area::all();
        return view("service_manager.create",compact('areas'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            "name"=>"required|max:191",
            "mobile_number"=>"required|max:11|min:11|unique:service_manager",
            "area_id"=>"required|numeric|exists:areas,id",
        ]);

        $serviceManager                   = new ServiceManager;
        $serviceManager->name             = $request->name;
        $serviceManager->mobile_number    = $request->mobile_number;
        $serviceManager->area_id          = $request->area_id;
        $serviceManager->save();

        Session::flash("success", "Service Manager Information Created Successfully !");
        return redirect()->route('service-manager.index');

    }

    public function show($id)
    {
        $serviceManager = ServiceManager::find($id);
        return view('service_manager.show',compact('serviceManager'));
    }

    public function edit($id)
    {
        $serviceManager = ServiceManager::find($id);
        $areas = Area::all();
        return view('service_manager.edit',compact('serviceManager','areas'));
    }

    public function update(Request $request, $id)
    {

        $this->validate($request,[
            "name"=>"required|max:191",
            "mobile_number"=>"required|max:11|min:11|unique:service_manager,mobile_number,".$id,
            "area_id"=>"required|numeric",
        ]);

        $serviceManager = ServiceManager::find($id);
        $serviceManager->name=$request->name;
        $serviceManager->mobile_number=$request->mobile_number;
        $serviceManager->area_id=$request->area_id;
        $serviceManager->save();

        Session::flash("success", "Service Manager Information Updated Successfully !");
        return redirect()->route('service-manager.index');
    }

    public function destroy($id)
    {
        $serviceManager = ServiceManager::find($id);
        $serviceManager->delete();
        Session::flash("success", "Service Manager Information Deleted Successfully !");
        return response()->json(['msg' => 'Data deleted successfully'], 200);
    }
}
