<?php

namespace App\Http\Controllers;

use App\Exports\TractorServiceExport;
use App\ServiceType;
use App\ServicingType;
use App\ServiseDetails;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class TractorServiceDetailsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $tractor_details = ServiseDetails::with('topic','servicing_type')
            ->orderBy('created_at','desc')
            ->get();
        return view("tractor_service.list",compact('tractor_details'));
    }

    public function create()
    {
        $topics = Topic::all();
        $servicing_types = ServicingType::all();
        return view("tractor_service.create",compact('topics','servicing_types'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'from_hr' => 'required',
            'to_hr' => 'required',
            'fixed_hr' => 'required',
            'topic_id' => 'required',
            'servicing_type_id' => 'required',
        ]);

        $service_detail                    = new ServiseDetails();
        $service_detail->topic_id          = $request->topic_id;
        $service_detail->from_hr           = $request->from_hr;
        $service_detail->to_hr             = $request->to_hr;
        $service_detail->fixed_hr          = $request->fixed_hr;
        $service_detail->servicing_type_id = $request->servicing_type_id;
        $service_detail->save();

        Session::flash("success", "Created Successfully !");
        return redirect()->route('tractor-service-details.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $service_detail = ServiseDetails::find($id);
        $topics = Topic::all();
        $servicing_types = ServicingType::all();
        return view("tractor_service.edit",compact('service_detail','topics','servicing_types'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'from_hr' => 'required',
            'to_hr' => 'required',
            'fixed_hr' => 'required',
            'topic_id' => 'required',
            'servicing_type_id' => 'required',
        ]);

        $service_detail                    = ServiseDetails::find($id);
        $service_detail->topic_id          = $request->topic_id;
        $service_detail->from_hr           = $request->from_hr;
        $service_detail->to_hr             = $request->to_hr;
        $service_detail->fixed_hr          = $request->fixed_hr;
        $service_detail->servicing_type_id = $request->servicing_type_id;
        $service_detail->save();

        Session::flash("success", "Updated Successfully !");
        return redirect()->route('tractor-service-details.index');
    }

    public function destroy($id)
    {
        $service_detail = ServiseDetails::find($id);
        $service_detail->delete();
        return response()->json(['msg' => 'Data deleted successfully'], 200);
    }

    public function exportData(){
        return Excel::download(new TractorServiceExport, 'TractorService.xlsx');
//        $tractor_details = ServiseDetails::with('topic','servicing_type')->orderBy('created_at','desc')->get();
//        $result = [];
//        foreach ($tractor_details as $value){
//            $result[] = [
//                'topic'=>isset($value->topic) ? $value->topic->name : '',
//                'From_hr'=>$value->from_hr,
//                'to_hr'=>$value->to_hr,
//                'fixed_hr'=>$value->fixed_hr,
//                'servicing_type'=>isset($value->servicing_type) ? $value->servicing_type->name: '',
//            ];
//        }
//        $filename = 'Tractor Service Details';
//        $this->exportexcel($result,$filename);
    }

    function exportexcel($result, $filename){
        $arrayheading[0] = !empty($result) ? array_keys($result[0]) : [];
        $result = array_merge($arrayheading, $result);

        header("Content-Disposition: attachment; filename=\"{$filename}.xls\"");
        header("Content-Type: application/vnd.ms-excel;");
        header("Pragma: no-cache");
        header("Expires: 0");
        $out = fopen("php://output", 'w');
        foreach ($result as $data) {
            fputcsv($out, $data, "\t");
        }
        fclose($out);
        exit();
    }
}
