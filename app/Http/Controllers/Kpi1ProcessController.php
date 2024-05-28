<?php

namespace App\Http\Controllers;

use App\BaseLine;
use App\IncentiveFactor;
use App\JobCard;
use Illuminate\Http\Request;
use App\Kpia;
use App\KpiaDetail;
use App\Target;
use App\User;
use App\UserKpiCode;
use App\Weight;
use App\KpiaIncentive;
use App\Helpers\Kpia\TechnicianKpiaHelper;
use App\Helpers\Kpia\TsoKpiaHelper;
use App\Helpers\Kpia\EngineerKpiaHelper;
use DB;
use App\KpiType;
use Illuminate\Support\Facades\Session;

class Kpi1ProcessController extends Controller{

     public function kpi_1_process_index(){
          $kpi_types = KpiType::orderBy('id','DESC')->get();
          return view('report.kpi1.kpi_1_process_index')
                 ->with('kpi_types',$kpi_types);
      }

    public function kpi_1_process(Request $request){
       $date = date('Y-m-d',strtotime($request->date));

       if($request->kpi_type_id == 3){
            $technician_kpia_helper = new TechnicianKpiaHelper;
            //$technician_kpia_helper->delete_all_processed_kpi($date);
            $technician_kpia_helper->technician_kpi_process($date);
            Session::flash("success", "Process Succcessfully !");
            return redirect()->back();
       }

       if($request->kpi_type_id == 2){
            $tso_kpia_helper = new TsoKpiaHelper;
            $tso_kpia_helper->tso_kpi_process($date);
            Session::flash("success", "Process Succcessfully !");
            return redirect()->back();
       }

       if($request->kpi_type_id == 1 || $request->kpi_type_id == 4 || $request->kpi_type_id == 5){
            $engineer_kpi_helper = new EngineerKpiaHelper;
            $engineer_kpi_helper->engineer_kpi_process($date,$request->kpi_type_id);
            Session::flash("success", "Process Succcessfully !");
            return redirect()->back();
       }

      dd('not kpi type given');
    }

   


}
