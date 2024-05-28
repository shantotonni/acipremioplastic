<?php

namespace App\Http\Controllers;


use App\KpiTopic;
use App\Target;
use Illuminate\Http\Request;
use App\User;
use App\UserKpi;
use App\UserKpiDetail;
use App\Helpers\TechnicianKpiHelper;
use DB;

class KpiProcessController extends Controller{

    public function technician_kpi_process(Request $request){
        $technician_kpi_helper = new TechnicianKpiHelper;
        $date = date('Y-m-d',strtotime($request->date));
        $technician_kpi_helper->delete_all_processed_kpi($date);
        $technician_kpi_helper->process_technician_kpi($date);
    }

  
}
