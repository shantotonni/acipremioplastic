<?php

namespace App\Http\Controllers;

use App\Area;
use App\DealerPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DealerPointController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $dealer_centers = DealerPoint::with('area')->orderBy('id','Desc')->paginate(10);
        return view("dealer_center.list",compact("dealer_centers"));
    }

    public function create()
    {
        $areas = Area::all();
        return view("dealer_center.create",compact('areas'));
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

        $dealer_center = new DealerPoint();
        $dealer_center->area_id = $request->area_id;
        $dealer_center->address = $request->address;
        $dealer_center->responsible_person = $request->responsible_person;
        $dealer_center->mobile = $request->mobile;
        $dealer_center->lat = $request->lat;
        $dealer_center->lon = $request->lon;
        $dealer_center->save();

        Session::flash("success", "Created Successfully !");
        return redirect()->route('dealer-point.index');
    }

    public function show(DealerPoint $dealerPoint)
    {
        //
    }

    public function edit(DealerPoint $dealerPoint)
    {
        $areas=Area::all();
        $dealer_center = $dealerPoint;
        return view("dealer_center.edit",compact('dealer_center','areas'));
    }

    public function update(Request $request, DealerPoint $dealerPoint)
    {
        $this->validate($request,[
            'area_id' => 'required',
            'address' => 'required',
            'responsible_person' => 'required',
            'mobile' => 'required',
            'lat' => 'required',
            'lon' => 'required',
        ]);

        $dealerPoint->area_id = $request->area_id;
        $dealerPoint->address = $request->address;
        $dealerPoint->responsible_person = $request->responsible_person;
        $dealerPoint->mobile = $request->mobile;
        $dealerPoint->lat = $request->lat;
        $dealerPoint->lon = $request->lon;
        $dealerPoint->save();

        Session::flash("success", "Updated Successfully !");
        return redirect()->route('dealer-point.index');
    }

    public function destroy(DealerPoint $dealerPoint)
    {
        $dealerPoint ->delete();
        Session::flash("success", "Deleted Successfully !");
        return response()->json([
            'msg'=>'Data Deleted Successfully',
            'status'=>1
        ]);
    }
}
