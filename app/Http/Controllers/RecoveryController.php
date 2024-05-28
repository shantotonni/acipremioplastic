<?php

namespace App\Http\Controllers;

use App\Exports\VisitResultExport;
use App\Recovery;
use App\Visit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RecoveryController extends Controller
{
    public function recovery(){
        $recoveries = Recovery::latest()->paginate(10);
        return view('recovery.recovery_list',compact('recoveries'));
    }

    public function visitResult(Request $request){
        $from_date = '';
        $to_date = '';
        $visits = Visit::query()->with('upazilla','visit_type','result','user');
        if ($request->has('from_date') && $request->has('to_date')){
            $from_date = date('Y-m-d',strtotime($request->from_date));
            $to_date = date('Y-m-d',strtotime($request->to_date));
            $visits = $visits->whereDate('created_at',">=",$from_date)->whereDate('created_at',"<=",$to_date);
        }
        $visits = $visits->latest()->paginate(15);
        return view('recovery.visit',compact('visits','from_date','to_date'));
    }

    public function export(Request $request)
    {
        // dd($request->all());
        return Excel::download(new VisitResultExport($request) ,'visit_result.xlsx');
    }
}
