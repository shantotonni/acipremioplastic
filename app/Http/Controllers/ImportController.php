<?php

namespace App\Http\Controllers;

use App\ImportHarvester;
use App\Imports\ChassisNumberWiseHarvesterImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function chassisNumberWiseHarvester(){
        $import_lists = ImportHarvester::orderBy('id','desc')->paginate(20);
        return view('import_chassis.import_chassis',compact('import_lists'));
    }

    public function chassisNumberWiseHarvesterStore(Request $request){
        Excel::import(new ChassisNumberWiseHarvesterImport(),request()->file('file'));
        Session::flash("success", "Imported Successfully !");
        return back();
    }
}
