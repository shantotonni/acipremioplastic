<?php

namespace App\Http\Controllers;

use App\Area;
use App\JobCard;
use App\Target;
use App\User;
use App\UserArea;
use Illuminate\Http\Request;
use DB;
use Auth;

class ReportController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
       
        if(Auth::user()->role_id == 1){
            $areas = Area::all();
            $technicians = User::where('role_id',3)->get();
         }else{
            $technicians = User::select('users.id','users.username','users.name')
                           ->join('user_territories','users.id','user_territories.user_id')
                           ->join('territories','territories.id','user_territories.territory_id')
                           ->join('areas','areas.id','territories.area_id')
                           ->join('user_areas','user_areas.area_id','areas.id')
                           ->where('user_areas.user_id',Auth::user()->id)
                           ->get();  
            $areas = Area::select('areas.id','areas.name')
                     ->join('user_areas','user_areas.area_id','areas.id')
                     ->where('user_areas.user_id',Auth::user()->id)
                     ->get(); 
         }
        
        return view('report.report_index')
                ->with('areas',$areas)
                ->with('technicians',$technicians);
    }

    public function area_wise_report(Request $request){

        $month = date('m',strtotime($request->date)); 
        $year = date('Y',strtotime($request->date));
        $area_ids = [];

         if(Auth::user()->role_id == 1){
            $area_ids = Area::pluck('id')->toArray();
        }else{
           $user_area = UserArea::where('user_id',Auth::user()->id)->first();
           $area_ids = Area::where('id',$user_area->area_id)->pluck('id')->toArray();
        }
        $area_ids = implode(', ', $area_ids);


        $job_cards = DB::select("SELECT a.name as area_name, 
                                        SUM(tar.total)as target_total,
    
                                        (SELECT count(jc.id) FROM job_cards jc 
                                             WHERE MONTH(jc.service_date) = '$month' and YEAR(jc.service_date) = '$year'
                                             AND jc.area_id = a.id
                                             AND jc.is_approved = 1
                                        ) as total
                                        FROM users u 
                                        JOIN user_territories ut ON u.id = ut.user_id
                                        JOIN territories t ON t.id = ut.territory_id
                                        JOIN areas a ON a.id = t.area_id
                                        LEFT JOIN targets tar 
                                             ON u.id = tar.technitian_id 
                                             AND MONTH(tar.date)  = '$month' and YEAR(tar.date) = '$year'
                                        WHERE u.role_id = 3
                                        AND a.id IN ($area_ids)
                                        GROUP BY a.id,a.name
                                      "  
                                    );



        return view('report.area_wise_report')->with('job_cards',$job_cards)->with('obj',$this);
    }

    public function territory_wise_report(Request $request){
        $job_cards =  DB::table('job_cards')
                 ->select('territories.id as territory_id','areas.name as area_name','territories.name as territory_name', DB::raw('count(*) as total'))
                 ->join('areas','areas.id','job_cards.area_id')
                 ->join('territories','territories.id','job_cards.territory_id')
                 ->groupBy('areas.name','areas.id','territories.name','territories.id')
                 ->where('job_cards.is_approved',1)
                 ->whereMonth('job_cards.service_date',date('m',strtotime($request->date)))
                 ->whereYear('job_cards.service_date',date('Y',strtotime($request->date)))
                 ->orderBy('areas.id','ASC');
        
        if(Auth::user()->role_id == 1){
            if($request->area_id != 'all'){
                $job_cards = $job_cards->where('areas.id',$request->area_id);
            }
            $job_cards = $job_cards->get();
        }else{
            $user_area = UserArea::where('user_id',Auth::user()->id)->first();
            $job_cards = $job_cards->where('areas.id',$user_area->area_id)->get();
        }
                 
        return view('report.territory_wise_report')->with('job_cards',$job_cards)->with('obj',$this);
    }

    // technitian wise monthly report
    public function technitian_wise_monthly_report(Request $request){
        $front_date = $request->date;
        $date = date('m',strtotime($request->date)); 
        $year = date('Y',strtotime($request->date));
        $area_ids = [];
        if(Auth::user()->role_id == 1){
            if($request->area_id != 'all'){
                $area_ids = Area::where('id',$request->area_id)->pluck('id')->toArray();
            }else{
                $area_ids = Area::pluck('id')->toArray();
            }
            
        }else{
            $user_area = UserArea::where('user_id',Auth::user()->id)->first();
            $area_ids = Area::where('id',$user_area->area_id)->pluck('id')->toArray();
        }

        $area_ids = implode(', ', $area_ids);

        $job_cards = DB::select("SELECT u.username,u.name,a.name as area_name, 
                                        tar.date, COALESCE(tar.total,0)as target_total,
    
                                        (SELECT count(jc.id) FROM job_cards jc 
                                         WHERE MONTH(jc.service_date) = '$date' and YEAR(jc.service_date) = '$year'
                                             AND (jc.technitian_id = u.id OR jc.participant_id = u.id)
                                        ) as total
                                        FROM users u 
                                        JOIN user_territories ut ON u.id = ut.user_id
                                        JOIN territories t ON t.id = ut.territory_id
                                        JOIN areas a ON a.id = t.area_id
                                        LEFT JOIN targets tar 
                                             ON u.id = tar.technitian_id AND MONTH(tar.date)  = '$date' and YEAR(tar.date) = '$year'

                                        WHERE u.role_id = 3
                                        AND a.id IN ($area_ids)
                                      "  
                                    );

                   
        return view('report.technitian_wise_monthly_report')
            ->with('job_cards',$job_cards)
            ->with('front_date',$front_date)
            ->with('obj',$this);
    }

    public function technitian_wise_daily_report(Request $request){
        // $job_cards =  DB::table('job_cards')
        //          ->select('users.username as username','users.id as user_id','users.name as name','areas.name as area_name', DB::raw('count(*) as total'))
        //          ->join('areas','areas.id','job_cards.area_id')
        //          ->join('job_card_details','job_card_details.job_card_id','job_cards.id')
        //          ->join('users','users.id','job_card_details.user_id')
        //          ->groupBy('areas.name','areas.id','users.username','users.id','users.name')
        //          ->whereDate('job_cards.service_date',date('Y-m-d',strtotime($request->date)))
        //          ->where('job_cards.is_approved',1)
        //          ->orderBy('areas.id','ASC');
        // if(Auth::user()->role_id == 1){
        //         if($request->area_id != 'all'){
        //             $job_cards = $job_cards->where('areas.id',$request->area_id);
        //         }
        //         $job_cards = $job_cards->get();
        // }else{
        //         $user_area = UserArea::where('user_id',Auth::user()->id)->first();
        //         $job_cards = $job_cards->where('areas.id',$user_area->area_id)->get();
        // } 
        
        $date = date('Y-m-d',strtotime($request->date)); 
        $month = date('m',strtotime($request->date)); 
        $area_ids = [];
        if(Auth::user()->role_id == 1){
            if($request->area_id != 'all'){
                $area_ids = Area::where('id',$request->area_id)->pluck('id')->toArray();
            }else{
                $area_ids = Area::pluck('id')->toArray();
            }
            
        }else{
            $user_area = UserArea::where('user_id',Auth::user()->id)->first();
            $area_ids = Area::pluck('id')->where('id',$user_area->area_id)->toArray();
        }

        $area_ids = implode(', ', $area_ids);

        $job_cards = DB::select("SELECT u.username,u.name,a.name as area_name, 
                                        tar.date, COALESCE(tar.total,0)as target_total,
    
                                        (SELECT count(jc.id) FROM job_cards jc 
                                         WHERE jc.service_date = '$date'
                                             AND jc.is_approved = 1
                                             AND (jc.technitian_id = u.id OR jc.participant_id = u.id)
                                        ) as total
                                        FROM users u 
                                        JOIN user_territories ut ON u.id = ut.user_id
                                        JOIN territories t ON t.id = ut.territory_id
                                        JOIN areas a ON a.id = t.area_id
                                        LEFT JOIN targets tar 
                                             ON u.id = tar.technitian_id AND MONTH(tar.date)  = '$month'

                                        WHERE u.role_id = 3
                                        AND a.id IN ($area_ids)
                                        ORDER BY a.id
                                      "  
                                    );
                   
        return view('report.technitian_wise_daily_report')->with('job_cards',$job_cards)->with('obj',$this);
    }

    public function technitian_wise_monthly_csi(Request $request){
        $TypeName = $request->TypeName;
        $date = date('Ym',strtotime($request->date));
        $areas_str = $request->area_id == 'all' ? '' : implode(',',Area::where('id',$request->area_id)->pluck('id')->toArray());
        $job_cards = DB::select("exec doLoadTechnicianMonthCSIDetailsForTractor '$date','$areas_str','$TypeName'");
        //return $job_cards;
        return view('report.technitian_wise_monthly_csi')
            ->with('job_cards',$job_cards)
            ->with('TypeName',$TypeName)
            ->with('obj',$this);
    }

//    public function technitian_wise_monthly_csi_for_harvester(Request $request){
//        $date = date('Ym',strtotime($request->date));
//        $areas_str = $request->area_id == 'all' ? '' : implode(',',Area::where('id',$request->area_id)->pluck('id')->toArray());
//        $job_cards = DB::select("exec doLoadTechnicianMonthCSIDetailsForHarvester '$date','$areas_str'");
//        return view('report.technitian_wise_monthly_csi_for_harvester')->with('job_cards',$job_cards)->with('obj',$this);
//    }

    public function technitian_wise_monthly_six_hours(Request $request){
        $date = date('Ym',strtotime($request->date));
        $areas_str = $request->area_id == 'all' ? '' : implode(',',Area::where('id',$request->area_id)->pluck('id')->toArray());
        $job_cards = DB::select("exec doLoadAreaWiseSixHoursReport '$date','$areas_str'");
        return view('report.technitian_wise_monthly_six_hours')->with('job_cards',$job_cards)->with('obj',$this);
    }

    public function technitian_job_card_list(Request $request){

       $user = User::find($request->technitian_id);

       $date = date('m',strtotime($request->date));
       $year = date('Y',strtotime($request->date));
       $job_cards = DB::select("	
                SELECT jc.*,
                a.name as area_name,
                s.isCalled,
                e.name as engineer_name,
                tech.name as technitian_name,
                part.name as participant_name,
                t.name as territory_name,
                p.name as product_name,
                st.name as service_type_name,
                ct.name as call_type_name,
                six_hours.Marks as six_hours
                FROM job_cards jc
                --INNER JOIN job_card_details jcd ON jcd.job_card_id = jc.id 
                INNER JOIN areas a on a.id = jc.area_id 
                INNER JOIN territories t on t.id= jc.territory_id 
                INNER JOIN users e on  e.id = jc.engineer_id 
                INNER JOIN users tech on  tech.id = jc.technitian_id 
                INNER JOIN products p on p.id = jc.product_id
                INNER join call_types ct on ct.id = jc.call_type_id
                INNER join service_types st on st.id = jc.service_type_id
                LEFT  JOIN users part on  part.id = jc.participant_id 
                LEFT JOIN [192.168.100.60].dbCRM.dbo.tblCSIImportDealerService s ON s.ServiceNo = jc.job_card_no 
                
                LEFT JOIN (SELECT  
                    F.ServiceNo,A.Marks
                    FROM [192.168.100.60].dbCRM.dbo.tblCSIImportDealerService S
                    INNER JOIN [192.168.100.60].dbCRM.dbo.tblCSICustomerFedback F
                            ON S.ServiceNo = F.ServiceNo
                    INNER JOIN [192.168.100.60].dbCRM.dbo.tblCSIQuestionSetup Q
                            ON F.QuestionId = Q.Id
                    INNER JOIN [192.168.100.60].dbCRM.dbo.tblCSIAnswerSetup A
                            ON F.Answer = A.Answer
                    INNER JOIN job_cards jc 
                            ON jc.job_card_no = F.ServiceNo
                    INNER JOIN products p2 
                        ON p2.id = jc.product_id 
                    WHERE F.TypeName = p2.name AND A.TypeName=p2.name
                    AND Q.Id = 47 AND A.TypeName = 'Tractor'
                    AND S.ServiceNo = jc.job_card_no
                    AND jc.technitian_id = '$request->technitian_id'
                    AND S.isCalled = 1
                )as six_hours ON six_hours.ServiceNo = jc.job_card_no
               
               
                WHERE MONTH(jc.service_date) = '$date'
                AND  YEAR(jc.service_date) = '$year'
                AND (jc.technitian_id = '$request->technitian_id' OR jc.participant_id = '$request->technitian_id')
                ");

        return view('report.technitian_job_card_list')
               ->with('job_cards',$job_cards)
               ->with('user',$user)
               ->with('obj',$this);
        
    }

    public function area_wise_job_card_list(Request $request){
        if($request->area_id == 'all'){
            $job_cards = JobCard::with('engineer','product','technitian','territory','call_type','service_type')
            ->where('is_approved',$request->is_approved)
            ->whereMonth('service_date',date('m',strtotime($request->date)))
            ->whereYear('service_date',date('Y',strtotime($request->date)))
            ->get();
        }
        else{
            $job_cards = JobCard::with('engineer','product','technitian','territory','call_type','service_type')
                             ->where('area_id',$request->area_id)
                             ->where('is_approved',$request->is_approved)
                             ->whereMonth('service_date',date('m',strtotime($request->date)))
                             ->whereYear('service_date',date('Y',strtotime($request->date)))
                             ->get();
        }

        return view('report.area_wise_job_card_list')
                             ->with('job_cards',$job_cards)
                             ->with('obj',$this);

    }

    public function get_area_wise_target($area_id,$date){
         $target = Target::join('territories','territories.id','targets.territory_id')
                      ->where('territories.area_id',$area_id)
                      ->whereMonth('targets.date',date('m',strtotime($date)))
                      ->whereYear('date',date('Y',strtotime($date)))
                      ->sum('total');
        return $target ? $target : 0;
            
    }

    public function get_territory_wise_target($territory_id,$date){
        $target = Target::where('territory_id',$territory_id)
                     ->whereMonth('date',date('m',strtotime($date)))
                     ->whereYear('date',date('Y',strtotime($date)))
                     ->sum('total');
       return $target ? $target : 0;      
   }

   public function get_technitian_wise_target($technitian_id,$date){
    $target = Target::where('technitian_id',$technitian_id)
                 ->whereMonth('date',date('m',strtotime($date)))
                 ->whereYear('date',date('Y',strtotime($date)))
                 ->sum('total');
   return $target ? $target : 0;      

   }

   public function get_csi_of_job_card(Request $request){
    return DB::select("exec get_csi_of_job_card '$request->job_card_no'");
   }

  public function technicianWiseTimelyHarvesterService(Request $request){
      $date = date('Ym',strtotime($request->date));
      $technitian_id = $request->technitian_id ? User::where('id',$request->technitian_id)->first()->id : 0;

      $job_cards = DB::select("exec doLoadATechnicianWiseTimelyHarvesterService '$date','$technitian_id'");
     // dd($job_cards);
      return view('report.technician_wise_timely_harvester_service')->with('job_cards',$job_cards)->with('obj',$this);
  }
}
