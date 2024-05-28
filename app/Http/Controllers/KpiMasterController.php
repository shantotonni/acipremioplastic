<?php

namespace App\Http\Controllers;

use App\Designation;
use App\Imports\KpiMasterImport;
use App\KpiMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class KpiMasterController extends Controller
{
    public function index(Request $request)
    {   
        $request['designation'] = null;
        $request['date'] = null;
        $inputs = $request->all();
        $kpis = KpiMaster::paginate(10);
        $designations = Designation::whereNotIn('name',['TSE','Jr.SE','SE','Sr.SE','TMS','Sr.TMS'] )->get();

        return view('kpi_master.index', compact('kpis','designations', 'inputs'));
    }

    public function search(Request $request)
    {
        $request['designation'] = null;
        $request['date'] = null;
        if($request->has('searchInput')){
            Session::put('searchInput', $request['searchInput']);
        }
        $inputs['searchInput'] = Session::get('searchInput');
        $inputs['date'] = null;
        $inputs['designation'] = null;
        $kpis = KpiMaster::where('staff_id', Session::get('searchInput'))->paginate(10);
        $designations = Designation::whereNotIn('name',['TSE','Jr.SE','SE','Sr.SE','TMS','Sr.TMS'] )->get();

        return view('kpi_master.index', compact('kpis','designations', 'inputs'));
    }
    public function getFilteredData(Request $request)
    {
        if($request->has('date')){
            Session::put('date', $request['date']);
            Session::put('designation', $request['designation']);
        }
        $inputs['date'] = Session::get('date');
        $inputs['designation'] = Session::get('designation');
       
        $kpis = KpiMaster::where('designation', Session::get('designation'))->whereDate('period', Session::get('date'))->paginate(10);
        $designations = Designation::whereNotIn('name',['TSE','Jr.SE','SE','Sr.SE','TMS','Sr.TMS'] )->get();
        return view('kpi_master.index', compact('kpis','designations', 'inputs'));
    }

    public function import(Request $request) 
    {
        $file = $request->file('filename');

        Excel::import(new KpiMasterImport($request->date), $file);
        
        return redirect(route('kpi_master'))->with('success', 'Uploaded Successfully');
    }

    public function details($id)
    {
        $kpi = KpiMaster::find($id);

        if($kpi->designation=='TSE' || $kpi->designation=='Jr.SE' || $kpi->designation=='SE' || $kpi->designation=='Sr.SE' || $kpi->designation=='TMS' || $kpi->designation=='Sr.TMS' ){
            return view('kpi_master.engineer_details', compact('kpi'));
        }
        elseif($kpi->designation=='TSA' || $kpi->designation=='Sr.TSA' || $kpi->designation=='PD'){
            $designationBase = Designation::where('name', $kpi->designation)->first();
            return view('kpi_master.technician_details', compact('kpi', 'designationBase'));
        }
        elseif($kpi->designation=='TSO' || $kpi->designation=='Sr.TSO' || $kpi->designation=='SS'){
            $designationBase = Designation::where('name', $kpi->designation)->first();
            return view('kpi_master.tso_details', compact('kpi', 'designationBase'));
        }
    }

    public function monthWiseKpiReport(Request $request)
    {
        $request['designation'] = null;
        $request['date'] = null;
        $inputs = $request->all();
        $designations = Designation::whereNotIn('name',['TSE','Jr.SE','SE','Sr.SE','TMS','Sr.TMS'] )->get();
        return view('kpi_master.month_wise_kpi_report', compact('designations', 'inputs'));
    }

    public function getSortedData(Request $request)
    {
        $data = [];
        $data['designations'] = Designation::whereNotIn('name',['TSE','Jr.SE','SE','Sr.SE','TMS','Sr.TMS'] )->get();
        $data['inputs'] = $request->all();

        if ( $request->isMethod('post') == true) {
            $data['result'] = KpiMaster::where('designation', $request->designation)->whereDate('period', $request->date)->orderBy('serial', 'ASC')->get()->toArray();
            $data['staff_id'] = array_unique(array_column($data['result'], 'staff_id'));
            $data['desig'] = Designation::where('name', $request->designation)->first();
            $data['period'] = $request->date;
        }
        return view('kpi_master.month_wise_kpi_report', $data);
    }

    //KPI Summary
    public function kpiSummary(Request $request)
    {
        $request['date'] = null;
        $inputs = $request->all();

        $designations = Designation::whereNotIn('name',['TSE','Jr.SE','SE','Sr.SE','TMS','Sr.TMS'] )->get();
        
        $data = [];
        $data['result'] = KpiMaster::orderBy('serial', 'ASC')->get();
        $data['desig'] = Designation::all();
        return view('kpi_master.kpi_summary', compact('designations', 'inputs'), $data);
    }

    public function kpiSummarySortedData(Request $request)
    {
        $inputs = $request->all();
        $designations = Designation::whereNotIn('name',['TSE','Jr.SE','SE','Sr.SE','TMS','Sr.TMS'] )->get();
        
        $data = [];
        $data['result'] = KpiMaster::whereDate('period', $request->date)->orderBy('serial', 'ASC')->get();
        $data['desig'] = Designation::all();
        return view('kpi_master.kpi_summary', compact('designations', 'inputs'), $data);
    }
}
