<?php

namespace App\Http\Controllers;

use App\Area;
use App\ServiceCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ServiceCenterController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $service_centers = ServiceCenter::with('area')->orderBy('id','Desc')->paginate(10);
        return view("service_center.list",compact("service_centers"));
    }

    public function create()
    {
        $areas = Area::all();
        return view("service_center.create",compact('areas'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'area_id' => 'required',
            'address' => 'required',
            'responsible_person' => 'required',
            'mobile' => 'required',
            'lat' => 'required',
            'lon' => 'required',
        ]);

        $service_center= new ServiceCenter();
        $service_center->area_id = $request->area_id;
        $service_center->address = $request->address;
        $service_center->responsible_person = $request->responsible_person;
        $service_center->mobile = $request->mobile;
        $service_center->lat = $request->lat;
        $service_center->lon = $request->lon;
        $service_center->save();

        Session::flash("success", "Created Successfully !");
        return redirect()->route('service-center.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $areas=Area::all();
        $service_center = ServiceCenter::findOrFail($id);
        return view("service_center.edit",compact('service_center','areas'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'area_id' => 'required',
            'address' => 'required',
            'responsible_person' => 'required',
            'mobile' => 'required',
            'lat' => 'required',
            'lon' => 'required',
        ]);

        $service_center= ServiceCenter::findOrFail($id);
        $service_center->area_id = $request->area_id;
        $service_center->address = $request->address;
        $service_center->responsible_person = $request->responsible_person;
        $service_center->mobile = $request->mobile;
        $service_center->lat = $request->lat;
        $service_center->lon = $request->lon;
        $service_center->save();

        Session::flash("success", "Updated Successfully !");
        return redirect()->route('service-center.index');
    }

    public function destroy($id)
    {
        $service_center = ServiceCenter::findOrFail($id);
        $service_center ->delete();
        Session::flash("success", "Deleted Successfully !");
        return response()->json([
           'status'=>1,
           'msg'=>'Data Deleted Successfully'
        ]);
    }
}
