<?php

namespace App\Http\Controllers;

use App\Area;
use App\Customer;
use App\Product;
use App\ShowroomCentre;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ShowroomController extends Controller
{
    public function index()
    {
        $showrooms = ShowroomCentre::orderBy('id','desc')->paginate(10);
        return view('showroom.list',compact('showrooms'));
    }

    public function create()
    {
        $areas = Area::all();
        return view("showroom.create",compact('areas'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            "name"=>"required|max:191",
            "mobile_number"=>"required|max:11|min:11|unique:showroom_centre",
            "area_id"=>"required|numeric|exists:areas,id",
            "address"=>"required",
            "lat"=>"required",
            "lon"=>"required"
        ]);

        $showroom                   = new ShowroomCentre;
        $showroom->name             = $request->name;
        $showroom->mobile_number    = $request->mobile_number;
        $showroom->area_id          = $request->area_id;
        $showroom->address          = $request->address;
        $showroom->lat              = $request->lat;
        $showroom->lon              = $request->lon;
        $showroom->save();

        Session::flash("success", "Showroom Created Successfully !");
        return redirect()->route('showrooms.index');

    }

    public function show($id)
    {
        $showroom = ShowroomCentre::find($id);
        return view('showroom.show',compact('showroom'));
    }

    public function edit($id)
    {
        $showroom = ShowroomCentre::find($id);
        $areas = Area::all();
        return view('showroom.edit',compact('showroom','areas'));
    }

    public function update(Request $request, $id)
    {

        $this->validate($request,[
            "name"=>"required|max:191",
            "mobile_number"=>"required|max:11|min:11|unique:showroom_centre,mobile_number,".$id,
            "area_id"=>"required|numeric",
            "address"=>"required",
            "lat"=>"required",
            "lon"=>"required"
        ]);

        $showroom = ShowroomCentre::find($id);
        $showroom->name=$request->name;
        $showroom->mobile_number=$request->mobile_number;
        $showroom->area_id=$request->area_id;
        $showroom->address=$request->address;
        $showroom->lat=$request->lat;
        $showroom->lon=$request->lon;
        $showroom->save();

        Session::flash("success", "Showroom Updated Successfully !");
        return redirect()->route('showrooms.index');
    }

    public function destroy($id)
    {
        $showroom = ShowroomCentre::find($id);
        $showroom->delete();
        Session::flash("success", "Showroom Deleted Successfully !");
        return response()->json(['msg' => 'Data deleted successfully'], 200);
    }
}
