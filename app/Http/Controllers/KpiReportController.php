<?php

namespace App\Http\Controllers;

use App\KpiTopic;
use App\UserKpi;
use App\UserKpiDetail;
use App\BaseLine;
use App\KpiGroup;
use App\UserKpiBaseLine;
use Illuminate\Http\Request;

class KpiReportController extends Controller{
    public function index(){
        return view('report.kpi.kpi_report_index');
    }

    public function kpi_report_show(Request $request){
        $user_kpis = UserKpi::all();
        return view('report.kpi.technician_kpi',compact('user_kpis'))->with('obj',$this);
    }

    public function get_kpi_details_kpi_groups($user_kpi_id){
        return   KpiGroup::join('user_kpi_details','user_kpi_details.kpi_group_id','kpi_groups.id')
                     ->select('kpi_groups.id','kpi_groups.name')
                     ->where('user_kpi_details.user_kpi_id',$user_kpi_id)
                     ->where('kpi_groups.id' ,'!=',2)
                     ->distinct()
                     ->get();
    }

    public function get_user_base_line_amount($user_kpi_id,$kpi_group_id){
        $baseline = UserKpiBaseLine::where('user_kpi_id',$user_kpi_id)
                    ->where('kpi_group_id',$kpi_group_id)
                    ->first();
        return $baseline ? $baseline->amount : 0;
    }

    public function get_kpi_detail_of_group($user_kpi_id,$kpi_group_id){
       //return [1,2];
       return UserKpiDetail::where('kpi_group_id',$kpi_group_id)
                             ->where('user_kpi_id',$user_kpi_id)
                             ->where('kpi_topic_id','!=',6)
                             ->where('kpi_topic_id','!=',7)
                             ->get();
    }

    
}


