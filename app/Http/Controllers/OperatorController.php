<?php

namespace App\Http\Controllers;

use App\Area;
use App\District;
use App\Exports\OperatorInfoExport;
use App\OperatorInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Matrix\Operators\Operator;
use Image;
use Maatwebsite\Excel\Facades\Excel;

class OperatorController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function operatorList(Request $request)
    {
//        $from_date = $request->from_date ? date('Y-m-d',strtotime($request->from_date)) : date('Y-m-01', strtotime(date('Y-m-01').' -1 month'));
//        $to_date = $request->to_date ? date('Y-m-d',strtotime($request->to_date)) : date('Y-m-d') ;
//        $mobile = $request->mobile ? $request->mobile : 0;

        $operators = OperatorInformation::query()->with('area_name', 'district_name');
        $from_date = '';
        $to_date = '';

        if ($request->filter == 'filter'){
            $from_date = date('Y-m-d',strtotime($request->from_date));
            $to_date = date('Y-m-d',strtotime($request->to_date));
            $mobile = $request->mobile;

            if($mobile){
                $operators = $operators->where('mobile',$mobile);
            }
            if ($from_date && $to_date){
                $operators = $operators->whereDate('training_date',">=",$from_date)
                    ->whereDate('training_date',"<=",$to_date);
            }
        }

        $operators = $operators->orderBy('id','Desc')->paginate(12);

        // dd($operators);
        return view('operator.list', compact('operators','from_date','to_date'));
    }

    public function operatorEdit($id)
    {
        $operator = OperatorInformation::findOrFail($id);
        $areas = Area::all();
        $districts = District::all();
        return view('operator.edit', compact('operator', 'areas', 'districts'));
    }

    public function operatorUpdate(Request $request)
    {
        $operator = OperatorInformation::findOrFail($request->id);
        $operator->operator_name =              $request->operator_name;
        $operator->village =                    $request->village;
        $operator->post_office =                $request->post_office;
        $operator->police_station =              $request->police_station;
        $operator->area =                          $request->area;
        $operator->district =                      $request->district;
        $operator->mobile =                      $request->mobile;
        $operator->training_level =              $request->training_level;
        $operator->training_date =               $request->training_date;
        $operator->training_venue =              $request->training_venue;
        $operator->total_training_days =           $request->total_training_days;
        $operator->operating_experience =          $request->operating_experience;
        $operator->education =                  $request->education;
        $operator->nid_no =                      $request->nid_no;
        $operator->owner_name =                      $request->owner_name;
        $operator->harvester_model =                      $request->harvester_model;
        $operator->chassis_number =                      $request->chassis_number;

        if ($request->hasFile('image_url')) {
            $image       = $request->file('image_url');
            $filename    = time().".". $image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());
            $image_resize->save(public_path('/operator_images/' .$filename));
            $operator->image_url = $filename;
        }

        $operator->save();
        Session::flash("success", "Updated Successfully !");
        return redirect("/operator-list");
    }

    public function operatorDelete($id)
    {
        OperatorInformation::where('id',$id)->delete();
        Session::flash("success", "Deleted Successfully !");
        return redirect("/operator-list");
    }

    public function OperatorInformationExport(Request $request){
        //dd($request->all());
        return Excel::download(new OperatorInfoExport($request), 'operator_list.xlsx');
    }
}
