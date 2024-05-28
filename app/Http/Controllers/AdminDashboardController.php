<?php

namespace App\Http\Controllers;

use App\JobCard;
use Illuminate\Http\Request;
use DB;

class AdminDashboardController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        
        $from_date = $request->from_date ? date('Y-m-d',strtotime($request->from_date)) : date('Y-m-01');
        $to_date = $request->to_date ? date('Y-m-d',strtotime($request->to_date)) : date('Y-m-d');

        $area_wise_services = JobCard::select('areas.name',DB::raw('count(8) as total'))
                              ->join('areas','areas.id','job_cards.area_id')
                              ->where('job_cards.is_approved',1)
                              ->whereDate('service_date','>=',$from_date)
                              ->whereDate('service_date','<=',$to_date)
                              ->groupBy('areas.name')
                              ->get();
        $area_wise_service_incomes = JobCard::select('areas.name',DB::raw('sum(job_cards.service_income) as total'))
                              ->join('areas','areas.id','job_cards.area_id')
                              ->where('job_cards.is_approved',1)
                              ->whereDate('service_date','>=',$from_date)
                              ->whereDate('service_date','<=',$to_date)
                              ->groupBy('areas.name')->get();
        $csi = $this->get_csi($from_date,$to_date);

        $six_hour = $this->get_six_hour($from_date,$to_date);

        return view('admin_dashboard')->with('area_wise_services',$area_wise_services)
                ->with('area_wise_service_incomes',$area_wise_service_incomes)
                ->with('csi',$csi)
                ->with('six_hour',$six_hour);
    }

    private function get_csi($from_date,$to_date){
        return DB::select("SELECT  
                SUM(A.Marks) as marks,COUNT(A.Marks) * 5 as outof
                FROM [192.168.100.60].dbCRM.dbo.tblCSIImportDealerService S
                INNER JOIN [192.168.100.60].dbCRM.dbo.tblCSICustomerFedback F
                        ON S.ServiceNo = F.ServiceNo
                INNER JOIN [192.168.100.60].dbCRM.dbo.tblCSIQuestionSetup Q
                        ON F.QuestionId = Q.Id
                INNER JOIN [192.168.100.60].dbCRM.dbo.tblCSIAnswerSetup A
                        ON F.Answer = A.Answer
                INNER JOIN job_cards jc 
                        ON jc.job_card_no = F.ServiceNo
                INNER JOIN job_card_details jcd
                        ON jcd.job_card_id = jc.id
                INNER JOIN products p2 
                    ON p2.id = jc.product_id 
                INNER JOIN areas a2
                    ON a2.id = jc.area_id
                WHERE F.TypeName = p2.name AND A.TypeName=p2.name
                AND jc.service_date >= '$from_date'
                AND jc.service_date <= '$to_date'
                AND S.isCalled = 1");
    }

    private function get_six_hour($from_date,$to_date){
        return DB::select("SELECT  
                SUM(A.Marks) as marks,COUNT(A.Marks) * 5 as outof
                FROM [192.168.100.60].dbCRM.dbo.tblCSIImportDealerService S
                INNER JOIN [192.168.100.60].dbCRM.dbo.tblCSICustomerFedback F
                        ON S.ServiceNo = F.ServiceNo
                INNER JOIN [192.168.100.60].dbCRM.dbo.tblCSIQuestionSetup Q
                        ON F.QuestionId = Q.Id
                INNER JOIN [192.168.100.60].dbCRM.dbo.tblCSIAnswerSetup A
                        ON F.Answer = A.Answer
                INNER JOIN job_cards jc 
                        ON jc.job_card_no = F.ServiceNo
                INNER JOIN job_card_details jcd
                        ON jcd.job_card_id = jc.id
                
                INNER JOIN products p2 
                    ON p2.id = jc.product_id 
                INNER JOIN areas a2
                    ON a2.id = jc.area_id
                WHERE F.TypeName = p2.name 
                AND A.TypeName=p2.name
                AND Q.Id = 47 AND A.TypeName = 'Tractor'
                AND jc.service_date >= '$from_date'
                AND jc.service_date <= '$to_date'
                AND S.isCalled = 1");
    }
}
