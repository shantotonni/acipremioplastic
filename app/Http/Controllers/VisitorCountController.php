<?php

namespace App\Http\Controllers;

use App\ParticipantImage;
use App\TractorCheckList;
use App\TractorCompititorParticipantInfo;
use App\TractorCultivationTrailReport;
use App\TractorDemonstrationModelImage;
use App\TractorDemonstrationRecordEntry;
use App\TractorSalesInquiry;
use App\VisitorCount;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class VisitorCountController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
      
      $year = VisitorCount::whereYear('date',date('Y'))->sum('count');
      $month = VisitorCount::whereMonth('date',date('m'))->sum('count');
      $day = VisitorCount::whereDate('date',date('Y-m-d'))->first()->count;
      $every_day_of_current_month = DB::table('visitor_counts')->get(['date','count'])->groupBy(function($date) {
        return Carbon::parse($date->date)->format('m');
      });
      $previous_month_first_date = date("Y-m-d", strtotime("first day of previous month"));
      $previous_month_count = VisitorCount::whereYear('date',date('Y',strtotime($previous_month_first_date)))
                              ->whereMonth('date',date('m',strtotime($previous_month_first_date)))
                              ->sum('count');
      return  response()->json(['this_year'=>$year,
                         'this_month'=>$month,
                         'today'=>$day,
                         'previous_month_count'=>$previous_month_count,
                         'every_day_of_current_month'=>$every_day_of_current_month
                         ]);
    }

    public function demonstrationList(Request $request){
        $area_id = $request->area_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $TractorDemonstrationRecordEntry = TractorDemonstrationRecordEntry::query()->orderBy('id','desc')
            ->with('participant_info','trail_report','model_image','area','territory','sales');
        if (!empty($area_id)){
            $TractorDemonstrationRecordEntry = $TractorDemonstrationRecordEntry->where('area_id',$area_id);
        }
        if (!empty($from_date) && !empty($to_date)){
            $from_date = date('Y-m-d',strtotime($request->from_date));
            $to_date = date('Y-m-d',strtotime($request->to_date));
            $TractorDemonstrationRecordEntry = $TractorDemonstrationRecordEntry->whereBetween('date',[$from_date,$to_date]);
        }
        $TractorDemonstrationRecordEntry = $TractorDemonstrationRecordEntry->paginate(20);
        return view('demonstration.list',compact('TractorDemonstrationRecordEntry'));
    }

    public function demonstrationDetails($id){
        $TractorDemonstrationRecordEntryDetails = TractorDemonstrationRecordEntry::query()->orderBy('id','desc')
            ->with('participant_info','trail_report','model_image','area','territory','check_list','participant_image','sales')
            ->where('id',$id)->first();
        //dd($TractorDemonstrationRecordEntryDetails);
        return view('demonstration.details',compact('TractorDemonstrationRecordEntryDetails'));

    }

    public function demonstrationDelete($id){
       $TractorDemonstrationRecordEntry = TractorDemonstrationRecordEntry::where('id',$id)->first();
       if ($TractorDemonstrationRecordEntry){
           TractorSalesInquiry::where('TDREID',$id)->delete();
           TractorCheckList::where('TDREID',$id)->delete();
           ParticipantImage::where('TDREID',$id)->delete();
           TractorCultivationTrailReport::where('TDREID',$id)->delete();
           TractorDemonstrationModelImage::where('TDREID',$id)->delete();
           TractorCompititorParticipantInfo::where('TDREID',$id)->delete();
           TractorDemonstrationRecordEntry::where('id',$id)->delete();

           return redirect()->back();
       }


    }
}
