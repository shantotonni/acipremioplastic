<?php

namespace App\Http\Controllers;

use App\Area;
use App\Kpia;
use App\KpiType;
use App\User;
use Illuminate\Http\Request;

class Kpi1ReportController extends Controller{
    public function index(){
        $areas = Area::all();
        $users = User::select('id','name','username')
                ->where('users.is_active',1) 
                ->where('users.role_id','!=',1)
                ->whereNotIn('users.id',[1,2,3,4,5]) // test users
                ->get();
        $kpi_types = KpiType::orderBy('id','DESC')->get();
        return view('report.kpi1.kpi_1_report_index')
               ->with('users',$users)
               ->with('areas',$areas)
               ->with('kpi_types',$kpi_types);
    }

    public function kpi_1_report_show(Request $request){
        if($request->kpi_type_id == 1){
            $user_kpis = Kpia::where('kpi_type_id',1)
                         ->whereMonth('date',date('m',strtotime($request->date)))
                         ->whereYear('date',date('Y',strtotime($request->date)))
                         ->get();
            return view('report.kpi1.engineer_kpi',compact('user_kpis'))->with('obj',$this);
        }

        if($request->kpi_type_id == 2){
            $user_kpis = Kpia::where('kpi_type_id',2)
                         ->whereMonth('date',date('m',strtotime($request->date)))
                         ->whereYear('date',date('Y',strtotime($request->date)))
                         ->get();
            return view('report.kpi1.tso_kpi',compact('user_kpis'))->with('obj',$this);
         }

         if($request->kpi_type_id == 3){
            $user_kpis = Kpia::where('kpi_type_id',3)
                        ->whereMonth('date',date('m',strtotime($request->date)))
                        ->whereYear('date',date('Y',strtotime($request->date)))
                        ->get();
            return view('report.kpi1.technician_kpi',compact('user_kpis'))->with('obj',$this);
         } 
    }

    public function kpi_1_area_wise_ty_wise_show(Request $request){
        if($request->kpi_type_id == 1){
            $user_kpis = Kpia::where('kpi_type_id',1)
                         ->join('user_areas','user_areas.user_id','kpias.user_id')
                         ->where('user_areas.area_id',$request->area_id)
                         ->whereMonth('kpias.date',date('m',strtotime($request->date)))
                         ->whereYear('kpias.date',date('Y',strtotime($request->date)))
                         ->select('kpias.*')
                         ->get();
            return view('report.kpi1.engineer_kpi',compact('user_kpis'))->with('obj',$this);
        }

        if($request->kpi_type_id == 2){ //tso
            $user_kpis = Kpia::where('kpi_type_id',$request->kpi_type_id)
                        ->join('user_territories','user_territories.user_id','kpias.user_id')
                        ->join('territories','territories.id','user_territories.territory_id')
                        ->where('territories.area_id',$request->area_id)
                        ->whereMonth('kpias.date',date('m',strtotime($request->date)))
                         ->whereYear('kpias.date',date('Y',strtotime($request->date)))
                        ->select('kpias.*')
                        ->get();
            return view('report.kpi1.tso_kpi',compact('user_kpis'))->with('obj',$this);
         }

         if($request->kpi_type_id == 3){ // technician
            $user_kpis = Kpia::where('kpi_type_id',$request->kpi_type_id)
                         ->join('user_territories','user_territories.user_id','kpias.user_id')
                         ->join('territories','territories.id','user_territories.territory_id')
                         ->where('territories.area_id',$request->area_id)
                         ->whereMonth('kpias.date',date('m',strtotime($request->date)))
                         ->whereYear('kpias.date',date('Y',strtotime($request->date)))
                         ->select('kpias.*')
                         ->get();
            return view('report.kpi1.technician_kpi',compact('user_kpis'))->with('obj',$this);
         } 
    }

    public function kpi_1_report_user_wise_show(Request $request){
        $user_kpis = Kpia::where('user_id',$request->user_id)
                    ->whereMonth('date',date('m',strtotime($request->date)))
                    ->whereYear('date',date('Y',strtotime($request->date)))
                    ->get();
        return view('report.kpi1.technician_kpi',compact('user_kpis'))->with('obj',$this);
    }
}
