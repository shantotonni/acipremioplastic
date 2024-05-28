<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Resources\GetAll as GetAllResource;
use App\Product;
use App\ServiceType;
use App\CallType;
use App\User;
use App\UserToken;
use App\Http\Resources\User as UserResource;
use App\JobCard;
use App\Target;
use App\UserTerritory;
use DB;

class DashboardController extends Controller{
    

    public function get_technitian_dashboard_info_by_date(Request $request){

        $token = str_replace("token ","",$request->header('Authorization'));
        $user_token = UserToken::where('token',$token)->first();
        if(!$user_token){ return response()->json(['errors'=>"Unauthorized"],401);}
        $user = User::find($user_token->user_id);
        
        if($request->month && $request->year){
           $date = '01-'.$request->month.'-'.$request->year;
        }else{
           $date = date('d-m-Y');
        }

        return response()->json(['data'=>[
            'monthly_target'=>$this->given_month_target($user->id,$date),
            'monthly_done'=>$this->given_month_done($user->id,$date),
            'yearly_target'=>$this->given_year_target($user->id,$date),
            'yearly_done'=>$this->given_year_done($user->id,$date),
            'monthly_six_hour_ratio'=> 100.1,
            'yearly_six_hour_ratio'=> 100.1,
            'monthly_csi' => 120.1,
            'yearly_csi' => 120.1,

            'user_monthly_six_hour_ratio'=>$this->get_monthly_six_hour_ratio($user->id,$date),
            'user_monthly_csi' => $this->get_monthly_csi($user->id,$date),

            'user_yearly_six_hour_ratio'=> $this->get_yearly_six_hour_ratio($user->id,$date),
            'user_yearly_csi' => $this->get_yearly_six_csi($user->id,$date)
      ]]);

    }

    public function given_month_target($user_id,$date){
        $year = date('Y',strtotime($date));
        $month = date('m',strtotime($date));
        $target =DB::select("SELECT u.username,u.name, tar.date, tar.total as total,tar.warranty_master_total as warranty_service,
                    tar.post_warranty_master_total as post_warranty_service, tar.tractor_warranty,tar.tractor_post_warranty,tar.nm_warranty, tar.nm_post_warranty,tar.service_income,
                        (SELECT count(jc.id) FROM job_cards jc 
                            WHERE MONTH(jc.service_date) = '$month' and YEAR(jc.service_date) = '$year'
                                AND (jc.technitian_id = u.id OR jc.participant_id = u.id)
                        ) as done_total
                        FROM users u 
                        JOIN user_territories ut ON u.id = ut.user_id
                        JOIN territories t ON t.id = ut.territory_id
                        LEFT JOIN targets tar 
                                ON u.id = tar.technitian_id AND MONTH(tar.date) = '$month' and YEAR(tar.date) = '$year'
                        WHERE u.id = '$user_id'"
                          );
      return $target ? [
          'warranty_service'=>(int)$target[0]->warranty_service,
          'post_warranty_service'=>(int)$target[0]->post_warranty_service,
          'tractor_warranty'=>(int)$target[0]->tractor_warranty,
          'tractor_post_warranty'=>(int)$target[0]->tractor_post_warranty,
          'nm_warranty'=>(int)$target[0]->nm_warranty,
          'nm_post_warranty'=>(int)$target[0]->nm_post_warranty,
          'service_income'=>(int)$target[0]->service_income,
          'total_new'=>$target[0]->tractor_warranty + $target[0]->tractor_post_warranty + $target[0]->nm_warranty + $target[0]->nm_post_warranty,
          'total'=>(int)$target[0]->total
      ] :
          [
              'warranty_service'=>0,
              'post_warranty_service'=>0,
              'tractor_warranty'=>0,
              'tractor_post_warranty'=>0,
              'nm_warranty'=>0,
              'nm_post_warranty'=>0,
              'service_income'=>0,
              'total'=>0,
              'total_new'=>0,
          ];
    }

    public function given_month_done($user_id,$date){

      $year = date('Y',strtotime($date));
      $month = date('m',strtotime($date));
      $job_cards = DB::select("EXEC doLoadTechnicianMonthlyDoneJobCard '$month', '$year', '$user_id' ");

      //return $job_cards;

      $post_warranty_service = 0;
      $warranty_service = 0;

      $tractor_warranty_service = 0;
      $tractor_post_warranty_service = 0;
      $harvester_and_others_warranty_service = 0;
      $harvester_and_others_post_warranty_service = 0;

      $service_income = 0;

      foreach($job_cards as $job_card){
        $service_income += $job_card->total_service_cost;

        if($job_card->code == 'warranty_service') $warranty_service += $job_card->count_totla ? $job_card->count_totla : 0 ;
        if($job_card->code == 'post_warranty_service') $post_warranty_service += $job_card->count_totla ? $job_card->count_totla : 0 ;

        //for tractor
        if($job_card->code == 'warranty_service' && $job_card->product_id == 1) $tractor_warranty_service += $job_card->count_totla ? $job_card->count_totla : 0 ;
        if($job_card->code == 'post_warranty_service' && $job_card->product_id == 1) $tractor_post_warranty_service += $job_card->count_totla ? $job_card->count_totla : 0 ;

        //for harvester
        if($job_card->code == 'warranty_service' && $job_card->product_id != 1) $harvester_and_others_warranty_service += $job_card->count_totla ? $job_card->count_totla : 0 ;
        if($job_card->code == 'post_warranty_service' && $job_card->product_id != 1) $harvester_and_others_post_warranty_service += $job_card->count_totla ? $job_card->count_totla : 0 ;
      }

      return ['warranty_service'=>(int)$warranty_service,
              'post_warranty_service'=>(int)$post_warranty_service,
              'tractor_warranty'=>(int)$tractor_warranty_service,
              'tractor_post_warranty'=>(int)$tractor_post_warranty_service,
              'nm_warranty'=>(int)$harvester_and_others_warranty_service,
              'nm_post_warranty'=>(int)$harvester_and_others_post_warranty_service,
              'service_income'=>(int)$service_income,
              'total'=>(int)$warranty_service + (int)$post_warranty_service,
              'total_new'=>$tractor_warranty_service + $tractor_post_warranty_service + $harvester_and_others_warranty_service + $harvester_and_others_post_warranty_service,
            ];
    }

    public function given_year_target($user_id,$date){
        $year = date('Y',strtotime($date));

        $target =DB::select("SELECT u.username,u.name, tar.date, tar.total as total,tar.warranty_master_total as warranty_service,
                    tar.post_warranty_master_total as post_warranty_service, tar.tractor_warranty,tar.tractor_post_warranty,tar.nm_warranty, tar.nm_post_warranty,tar.service_income,
                        (SELECT count(jc.id) FROM job_cards jc 
                            WHERE YEAR(jc.service_date) = '$year'
                                AND (jc.technitian_id = u.id OR jc.participant_id = u.id)
                        ) as done_total
                        FROM users u 
                        JOIN user_territories ut ON u.id = ut.user_id
                        JOIN territories t ON t.id = ut.territory_id
                        LEFT JOIN targets tar 
                                ON u.id = tar.technitian_id AND YEAR(tar.date) = '$year'
                        WHERE u.id = '$user_id'"
        );

      $warranty_service = 0;
      $post_warranty_service = 0;
      $tractor_warranty = 0;
      $tractor_post_warranty = 0;
      $nm_warranty = 0;
      $nm_post_warranty = 0;
      $service_income = 0;
      $total_target = 0;

      foreach ($target as $value){
          $warranty_service += $value->warranty_service;
          $post_warranty_service += $value->post_warranty_service;
          $tractor_warranty += $value->tractor_warranty;
          $tractor_post_warranty += $value->tractor_post_warranty;
          $nm_warranty += $value->nm_warranty;
          $nm_post_warranty += $value->nm_post_warranty;
          $service_income += $value->service_income;
          $total_target += $value->total;
      }

      return $target ? [
          'warranty_service'=>(int)$warranty_service,
          'post_warranty_service'=>(int)$post_warranty_service,
          'tractor_warranty'=>(int)$tractor_warranty,
          'tractor_post_warranty'=>(int)$tractor_post_warranty,
          'nm_warranty'=>(int)$nm_warranty,
          'nm_post_warranty'=>(int)$nm_post_warranty,
          'service_income'=>(int)$service_income,
          'total_new'=> $tractor_warranty + $tractor_post_warranty + $nm_warranty + $nm_post_warranty,
          'total'=>(int)$total_target
      ] :
          [
              'warranty_service'=>0,
              'post_warranty_service'=>0,
              'tractor_warranty'=>0,
              'tractor_post_warranty'=>0,
              'nm_warranty'=>0,
              'nm_post_warranty'=>0,
              'service_income'=>0,
              'total_new'=>0,
              'total'=>0
          ];
    }

    public function given_year_done($user_id,$date){

        $year = date('Y',strtotime($date));
        $job_cards = DB::select("EXEC doLoadTechnicianYearlyDoneJobCard '$year', '$user_id' ");

        $post_warranty_service = 0;
        $warranty_service = 0;

        $tractor_warranty_service = 0;
        $tractor_post_warranty_service = 0;
        $harvester_and_other_warranty_service = 0;
        $harvester_and_other_post_warranty_service = 0;
        $service_income = 0;

      foreach($job_cards as $job_card){
        $service_income += $job_card->total_service_cost;

        if($job_card->code == 'post_warranty_service') $post_warranty_service = $job_card->count_totla ? $job_card->count_totla : 0 ;
        if($job_card->code == 'warranty_service') $warranty_service = $job_card->count_totla ? $job_card->count_totla : 0 ;

        //for tractor
        if($job_card->code == 'warranty_service' && $job_card->product_id == 1) $tractor_warranty_service += $job_card->count_totla ? $job_card->count_totla : 0 ;
        if($job_card->code == 'post_warranty_service' && $job_card->product_id == 1) $tractor_post_warranty_service += $job_card->count_totla ? $job_card->count_totla : 0 ;

        //for harvester
        if($job_card->code == 'warranty_service' && $job_card->product_id != 1) $harvester_and_other_warranty_service += $job_card->count_totla ? $job_card->count_totla : 0 ;
        if($job_card->code == 'post_warranty_service' && $job_card->product_id != 1) $harvester_and_other_post_warranty_service += $job_card->count_totla ? $job_card->count_totla : 0 ;
      }

      return ['warranty_service'=>(int)$warranty_service,
              'post_warranty_service'=>(int)$post_warranty_service,
              'tractor_warranty'=>(int)$tractor_warranty_service,
              'tractor_post_warranty'=>(int)$tractor_post_warranty_service,
              'nm_warranty'=>(int)$harvester_and_other_warranty_service,
              'nm_post_warranty'=>(int)$harvester_and_other_post_warranty_service,
              'service_income'=>(int)$service_income,
              'total_new'=>$tractor_warranty_service + $tractor_post_warranty_service + $harvester_and_other_warranty_service + $harvester_and_other_post_warranty_service,
              'total'=>(int)$warranty_service + (int)$post_warranty_service,
            ];
    }

    private function get_monthly_six_hour_ratio($id,$date){
      $date = date('Y-m-d',strtotime($date));
      $data = ['Total'=>0,'Y'=>0,'N'=>0];
      $q = DB::select("exec user_wise_monthly_six_hours_report '$date','$id'");
        if(count($q)){
          $data = ['Total'=>$q[0]->Y + $q[0]->N  ,'Y'=>(int)$q[0]->Y,'N'=>(int)$q[0]->N];
        }
      return $data;
    }

    private function get_monthly_csi($id,$date){
      $date = date('Y-m-d',strtotime($date));
      $data = ['Marks'=>0,'OutOf'=>0];
      $q = DB::select("exec user_wise_monthly_csi_report '$date','$id'");
        if(count($q)){
            $data = ['Marks'=>(int)$q[0]->TotalMarks,'OutOf'=>(int)$q[0]->OutOf];
        }
      return $data;
    }

    private function get_yearly_six_hour_ratio($id,$date){
      $date = date('Y-m-d',strtotime($date));
      $data = ['Total'=>0,'Y'=>0,'N'=>0];
      $q = DB::select("exec user_wise_yearly_six_hours_report '$date','$id'");
      if(count($q)){
          $data = ['Total'=>$q[0]->Y + $q[0]->N  ,'Y'=>(int)$q[0]->Y,'N'=>(int)$q[0]->N];
      }
      return $data;
    }
    
    private function get_yearly_six_csi($id,$date){
      $date = date('Y-m-d',strtotime($date));
      $data = ['Marks'=>0,'OutOf'=>0];
      $q = DB::select("exec user_wise_yearly_csi_report '$date','$id'");
        if(count($q)){
            $data = ['Marks'=>(int)$q[0]->TotalMarks,'OutOf'=>(int)$q[0]->OutOf];
        }
      return $data;
    }


}
