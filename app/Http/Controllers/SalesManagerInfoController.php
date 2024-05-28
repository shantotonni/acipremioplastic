<?php

namespace App\Http\Controllers;

use App\Area;
use App\SalesManagerInfo;
use App\ShowroomCentre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SalesManagerInfoController extends Controller
{
    public function index()
    {
        $salesManagers = SalesManagerInfo::orderBy('id','desc')->paginate(10);
        return view('sales_manager.list',compact('salesManagers'));
    }

    public function create()
    {
        $areas = Area::all();
        return view("sales_manager.create",compact('areas'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            "name"=>"required|max:191",
            "mobile_number"=>"required|max:11|min:11|unique:sales_manager_info",
            "area_id"=>"required|numeric|exists:areas,id",
        ]);

        $salesManager                   = new SalesManagerInfo;
        $salesManager->name             = $request->name;
        $salesManager->mobile_number    = $request->mobile_number;
        $salesManager->area_id          = $request->area_id;
        $salesManager->save();

        Session::flash("success", "Sales Manager Information Created Successfully !");
        return redirect()->route('sales-manager-info.index');

    }

    public function show($id)
    {
        $salesManager = SalesManagerInfo::find($id);
        return view('sales_manager.show',compact('salesManager'));
    }

    public function edit($id)
    {
        $salesManager = SalesManagerInfo::find($id);
        $areas = Area::all();
        return view('sales_manager.edit',compact('salesManager','areas'));
    }

    public function update(Request $request, $id)
    {

        $this->validate($request,[
            "name"=>"required|max:191",
            "mobile_number"=>"required|max:11|min:11|unique:sales_manager_info,mobile_number,".$id,
            "area_id"=>"required|numeric",
        ]);

        $salesManager = SalesManagerInfo::find($id);
        $salesManager->name=$request->name;
        $salesManager->mobile_number=$request->mobile_number;
        $salesManager->area_id=$request->area_id;
        $salesManager->save();

        Session::flash("success", "Sales Manager Information Updated Successfully !");
        return redirect()->route('sales-manager-info.index');
    }

    public function destroy($id)
    {
        $salesManager = SalesManagerInfo::find($id);
        $salesManager->delete();
        Session::flash("success", "Sales Manager Information Deleted Successfully !");
        return response()->json(['msg' => 'Data deleted successfully'], 200);
    }
}
