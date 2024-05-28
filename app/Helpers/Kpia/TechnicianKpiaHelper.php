<?php 
namespace App\Helpers\Kpia;

use App\IncentiveFactor;
use App\JobCard;
use App\Kpia;
use App\KpiaDetail;
use App\Target;
use App\User;
use App\UserKpiCode;
use App\Weight;
use App\KpiaIncentive;
use App\BaseLine;
use App\TechnicianKpiAdjust;
use DB;

class TechnicianKpiaHelper{

    public function technician_kpi_process($date){

        $job_cards = $this->given_month_job($date);
        $users = User::select('users.id as id','targets.id as target_id',
                       'targets.warranty_master_total','targets.post_warranty_master_total',
                       'targets.service_income','targets.tractor_spare_parts_lubricants',
                       'targets.nm_pt_spare_parts_lubricants'
                        )
                       ->join('targets','targets.technitian_id','users.id')
                      // ->whereIn('users.id',$user_ids)
                       ->where('users.role_id',3)
                       ->where('users.is_active',1)
                       ->whereNotIn('users.id',[1,2])
                       ->whereMonth('targets.date',date('m',strtotime($date)))
                       ->whereYear('targets.date',date('Y',strtotime($date)))
                       ->get();

        $base_line = $this->get_base_line(3);
        $weight    = $this->get_weight(3);
        foreach($users as $user){
             $post_warranty_service =  $job_cards->where('user_id',$user->id)->where('service_master_code','post_warranty_service')->first();
             $post_warranty_service_count = $post_warranty_service ? $post_warranty_service->count : 0;
             $warranty_service =  $job_cards->where('user_id',$user->id)->where('service_master_code','warranty_service')->first();
             $warranty_service_count = $warranty_service ? $warranty_service->count : 0;

             $user_kpi_code = UserKpiCode::where('user_id',$user->id)->first();
             if($user_kpi_code)
             $this->manage_save_kpi($user,$date,$base_line,$weight,$user_kpi_code,$warranty_service_count,$post_warranty_service_count);
        }
    }


    private function manage_save_kpi($user,$date,$base_line,$weight,$user_kpi_code,$warranty_service_count,$post_warranty_service_count){
        
        $kpia = Kpia::whereMonth('date',date('m',strtotime($date)))
                      ->where('user_id',$user->id)->first();
        $kpia =  $kpia ? $kpia : new Kpia;
        
        $kpia->date=date("Y-m-01",strtotime($date));
        $kpia->user_id=$user->id;
        $kpia->kpi_type_id = 3; // technician
        $kpia->total_kpi_mark = 0;
        $kpia->total_incentive_bonus = 0;
        $kpia->total_incentive_amount = 0;
       
        //need to maintain order
        $kpia = $this->manage_service_income($user,$date,$base_line,$weight,$user_kpi_code,$kpia,$warranty_service_count,$post_warranty_service_count);
        $kpia = $this->manage_sales($user,$date,$base_line,$weight,$user_kpi_code,$kpia);
        
    
        $kpia->total_kpi_mark = (float)$kpia->service_f_score_total
                               +(float)$kpia->sp_tractor_plus_nmpt_f_score_total;

        

        $kpia->save();
        $kpia = $this->manage_incentive($kpia);
        $kpia->total_incentive_amount = (float)$kpia->service_income_total_incentive
                                        +(float)$kpia->sp_tractor_plus_nmpt_total_incentive
                                        +(float)$kpia->total_incentive_bonus;
        $kpia->save();

    }

    private function manage_service_income($user,$date,$base_line,$weight,$user_kpi_code,$kpia,$warranty_service_count,$post_warranty_service_count){
        //$job_card_done = $this->given_month_job_done($user->id,$date);

        $technician_kpi_adjust = TechnicianKpiAdjust::whereMonth('date',date('m',strtotime($date)))
                                                      ->whereYear('date',date('Y',strtotime($date)))
                                                      ->first();
        // service 
        //warranty service
        $kpia->service_ratio_ws_target= $user->warranty_master_total;
        $kpia->service_ratio_ws_actual=(float)$warranty_service_count + ($technician_kpi_adjust ? $technician_kpi_adjust->service_ratio_ws_actual : 0);
        $kpia->service_ratio_ws_weight=(int)$weight->service_ratio_ws_weight;
        $score = $kpia->service_ratio_ws_target > 0 ? (float)number_format(($kpia->service_ratio_ws_actual/$kpia->service_ratio_ws_target)*$kpia->service_ratio_ws_weight,2) : 0;
        $kpia->service_ratio_ws_score=$score;
        $kpia->service_ratio_ws_f_score= $score > $kpia->service_ratio_ws_weight ?  $kpia->service_ratio_ws_weight : $score ;
        
        //post warranty service
        $kpia->service_ratio_pws_target= (float)$user->post_warranty_master_total;
        $kpia->service_ratio_pws_actual= (float)$post_warranty_service_count + ($technician_kpi_adjust ? $technician_kpi_adjust->service_ratio_pws_actual : 0);
        $kpia->service_ratio_pws_weight=(int)$weight->service_ratio_pws_weight;
        $score = $kpia->service_ratio_pws_target > 0 ? (float)number_format(($kpia->service_ratio_pws_actual/$kpia->service_ratio_pws_target)*$kpia->service_ratio_pws_weight,2) : 0;
        $kpia->service_ratio_pws_score=$score;
        $kpia->service_ratio_pws_f_score= $score > $kpia->service_ratio_pws_weight ?  $kpia->service_ratio_pws_weight : $score ;
        
         
        $s = $this->get_monthly_six_hour_ratio($user->id,$date);
        $t = (float)$s['Total'] + ($technician_kpi_adjust ? $technician_kpi_adjust->satisfaction_index_six_target : 0);
        $actual = (float)$s['Y'] +  ($technician_kpi_adjust ? $technician_kpi_adjust->satisfaction_index_six_actual : 0);
        $kpia->satisfaction_index_six_target=$t;
        $kpia->satisfaction_index_six_actual=$actual;
        $kpia->satisfaction_index_six_weight=(int)$weight->satisfaction_index_six_weight;
        $score = $t > 0 ? (float)number_format(($actual/$t)*$kpia->satisfaction_index_six_weight,2) : 0;
        $kpia->satisfaction_index_six_score=$score;
        $kpia->satisfaction_index_six_f_score=$score > $kpia->satisfaction_index_six_weight ? $kpia->satisfaction_index_six_weight : $score;

        $s = $this->get_monthly_csi($user->id,$date);
        $t = (float)$s['OutOf']  + ($technician_kpi_adjust ? $technician_kpi_adjust->satisfaction_index_csi_target : 0);
        $actual = (float)$s['Marks']  + ($technician_kpi_adjust ? $technician_kpi_adjust->satisfaction_index_csi_actual : 0);
        $kpia->satisfaction_index_csi_target=$t;
        $kpia->satisfaction_index_csi_actual=$actual;
        $kpia->satisfaction_index_csi_weight=(int)$weight->satisfaction_index_csi_weight;
        $score = $t > 0 ? (float)number_format(($actual/$t)*$kpia->satisfaction_index_csi_weight,2) : 0;
        $kpia->satisfaction_index_csi_score=$score;
        $kpia->satisfaction_index_csi_f_score=$score > $kpia->satisfaction_index_csi_weight ? $kpia->satisfaction_index_csi_weight : $score;
        
        
        $t = $user->service_income;
        $actual = (float)$this->get_technician_service_income($user_kpi_code,$date);
        $kpia->service_income_target=$t;
        $kpia->service_income_actual=$actual;
        $kpia->service_income_weight=(int)$weight->service_income_weight;
        $score = $t > 0 ? (float)number_format(($actual/$t)*$kpia->service_income_weight,2) : 0;
        $kpia->service_income_score=$score;
        $kpia->service_income_f_score=$score > $kpia->service_income_weight ? $kpia->service_income_weight : $score;;
        //service end


        // engineer only
        $kpia->report_submission_weight=(int)$weight->report_submission_weight;
        $kpia->report_submission_score=0;
        $kpia->report_submission_f_score=0;
        $kpia->app_monitor_weight=(int)$weight->app_monitor_weight;
        $kpia->app_monitor_score=0;
        $kpia->app_monitor_f_score=0;
        $kpia->team_co_weight=(int)$weight->team_co_weight;
        $kpia->team_co_score=0;
        $kpia->team_co_f_score=0;
        // engineer only done

        $service_total_weight = 70;

        $kpia->service_income_base_line=(int)$base_line->service_income_base_line;
        $kpia->service_f_score_total=round($kpia->service_ratio_ws_f_score+
                                    $kpia->service_ratio_pws_f_score+
                                    $kpia->satisfaction_index_six_f_score+
                                    $kpia->satisfaction_index_csi_f_score+
                                    $kpia->service_income_f_score+
                                    $kpia->report_submission_f_score+
                                    $kpia->app_monitor_f_score+
                                    $kpia->team_co_f_score);

        $kpia->service_f_score_percent=round($kpia->service_f_score_total*100/$service_total_weight);
        $kpia->service_income_total_incentive= round($kpia->service_income_base_line * $kpia->service_f_score_total/$service_total_weight);


        return $kpia;
    }

    private function manage_sales($user,$date,$base_line,$weight,$user_kpi_code,$kpia){

        $t = (float)$user->tractor_spare_parts_lubricants;
        $actual = (float)$this->get_technician_tractor_sales_actual($user_kpi_code,$date); 
        $kpia->sp_tractor_target=$t;
        $kpia->sp_tractor_actual=$actual;
        $kpia->sp_tractor_weight=(int)$weight->sp_tractor_weight;
        $score = round($t > 0 ? (float)($actual/$t)*$kpia->sp_tractor_weight : 0);
        $kpia->sp_tractor_score=$score;
        $kpia->sp_tractor_f_score= round($score > $kpia->sp_tractor_weight ? $kpia->sp_tractor_weight : $score);

        // $kpia->sp_tractor_base_line=$base_line->sp_tractor_base_line;
        // $kpia->sp_tractor_f_score_total=$sp_tractor_f_score_total;
        // $kpia->sp_tractor_f_score_percent=$sp_tractor_f_score_percent;
        // $kpia->sp_tractor_total_incentive=$sp_tractor_total_incentive;

        $t = $user->nm_pt_spare_parts_lubricants;
        $actual = (float)$this->get_technician_nm_pt_sales_actual($user_kpi_code,$date); 
        $kpia->sp_nmpt_target=$t;
        $kpia->sp_nmpt_actual=$actual;
        $kpia->sp_nmpt_weight=(int)$weight->sp_nmpt_weight;
        $score = $t > 0 ? (float)round(($actual/$t)*$kpia->sp_nmpt_weight) : 0;
        $kpia->sp_nmpt_score=$score;
        $kpia->sp_nmpt_f_score= round($score > $kpia->sp_nmpt_weight ? $kpia->sp_nmpt_weight : $score);

        // $kpia->sp_nmpt_base_line=$sp_nmpt_base_line;
        // $kpia->sp_nmpt_f_score_total=$sp_nmpt_f_score_total;
        // $kpia->sp_nmpt_f_score_percent=$sp_nmpt_f_score_percent;
        // $kpia->sp_nmpt_total_incentive=$sp_nmpt_total_incentive;

        $kpia->sp_tractor_plus_nmpt_target =$kpia->sp_tractor_target + $kpia->sp_nmpt_target;
        $kpia->sp_tractor_plus_nmpt_actual =$kpia->sp_tractor_actual + $kpia->sp_nmpt_actual;
        $kpia->sp_tractor_plus_nmpt_weight =$kpia->sp_tractor_weight + $kpia->sp_nmpt_weight;
        $kpia->sp_tractor_plus_nmpt_score  =$kpia->sp_tractor_score + $kpia->sp_nmpt_score;
        $kpia->sp_tractor_plus_nmpt_f_score= $kpia->sp_tractor_plus_nmpt_score > $kpia->sp_tractor_plus_nmpt_weight ? $kpia->sp_tractor_plus_nmpt_weight :  $kpia->sp_tractor_plus_nmpt_score;

        $kpia->sp_tractor_plus_nmpt_base_line=$base_line->sp_tractor_plus_nmpt_base_line;
        $kpia->sp_tractor_plus_nmpt_f_score_total=$kpia->sp_tractor_plus_nmpt_f_score;
        $kpia->sp_tractor_plus_nmpt_f_score_percent=$kpia->sp_tractor_plus_nmpt_score*100/$kpia->sp_tractor_plus_nmpt_weight;
        $kpia->sp_tractor_plus_nmpt_total_incentive=$kpia->sp_tractor_plus_nmpt_base_line * $kpia->sp_tractor_plus_nmpt_f_score_total/$kpia->sp_tractor_plus_nmpt_weight ;

        return $kpia;
        // sales end
    }


    private function manage_incentive($kpia){
  
        $incentive_factors = IncentiveFactor::all();
        $total_incentive_bonus = 0;

        foreach($incentive_factors as $incentive_factor){
            if((float)$kpia->sp_tractor_plus_nmpt_f_score_percent >= (float)$incentive_factor->from &&  
               (float)$kpia->sp_tractor_plus_nmpt_f_score_percent <= (float)$incentive_factor->to){
                $tractor_and_nmpt_amount=$kpia->sp_tractor_plus_nmpt_total_incentive * $incentive_factor->multiplication_factor;
                $kpia_incentive =$this->manage_save_incentive($kpia->id,$incentive_factor,0,0,$tractor_and_nmpt_amount);
                $total_incentive_bonus += $kpia_incentive->tractor_and_nmpt; 
            }else{
                $this->manage_save_incentive($kpia->id,$incentive_factor,0,0,0);
            }
        }

        $kpia->total_incentive_bonus = $total_incentive_bonus;

    
        return $kpia;

    }

    private function manage_save_incentive($kpia_id,$incentive_factor,$tractor,$nmpt,$tractor_and_nmpt){
        $kpia_incentive = KpiaIncentive::where('kpia_id',$kpia_id)
                         ->where('incentive_factor_id',$incentive_factor->id)
                         ->first();
        $kpia_incentive = $kpia_incentive ? $kpia_incentive : new KpiaIncentive;
        $kpia_incentive->kpia_id=$kpia_id;
        $kpia_incentive->incentive_factor_id=$incentive_factor->id;
        $kpia_incentive->multiplier=$incentive_factor->multiplication_factor;
        $kpia_incentive->tractor=$tractor;
        $kpia_incentive->nmpt=$nmpt;
        $kpia_incentive->tractor_and_nmpt=$tractor_and_nmpt;
        $kpia_incentive->save();

        return $kpia_incentive;
        

    }


    private function get_base_line($kpi_type_id){
         return BaseLine::where('kpi_type_id',$kpi_type_id)->first();
    }

    private function get_weight($kpi_type_id){
        return Weight::where('kpi_type_id',$kpi_type_id)->first();
   }



    private function get_technician_service_income($user_kpi_code,$date){
        $from_date = date('Y-m-01',strtotime($date));
        $to_date   = date("Y-m-t", strtotime($from_date));
        $code = $user_kpi_code->service_income_code;
        return $this->get_sales_amount_of_code($code,$from_date,$to_date); 
    }

    private function get_technician_tractor_sales_actual($user_kpi_code,$date){
        $from_date = date('Y-m-01',strtotime($date));
        $to_date   = date("Y-m-t", strtotime($from_date));
        $total = 0;

        if($user_kpi_code->tractor_spare_parts_code)
            $total += $this->get_sales_amount_of_code($user_kpi_code->tractor_spare_parts_code,$from_date,$to_date);
        if($user_kpi_code->tractor_sonalika_lub_code)
            $total += $this->get_sales_amount_of_code($user_kpi_code->tractor_sonalika_lub_code,$from_date,$to_date);
        if($user_kpi_code->tractor_power_oil_code)
            $total += $this->get_sales_amount_of_code($user_kpi_code->tractor_power_oil_code,$from_date,$to_date);

        return $total;
    }

    private function get_technician_nm_pt_sales_actual($user_kpi_code,$date){
        $from_date = date('Y-m-01',strtotime($date));
        $to_date   = date("Y-m-t", strtotime($from_date));
        $total = 0;
        if($user_kpi_code->nm_spare_parts_code)
            $total += $this->get_sales_amount_of_code($user_kpi_code->nm_spare_parts_code,$from_date,$to_date);
        if($user_kpi_code->nm_power_oil_code)
            $total += $this->get_sales_amount_of_code($user_kpi_code->nm_power_oil_code,$from_date,$to_date);
        if($user_kpi_code->pt_spare_parts_code)
            $total += $this->get_sales_amount_of_code($user_kpi_code->pt_spare_parts_code,$from_date,$to_date);
        if($user_kpi_code->pt_power_oil_code)
            $total += $this->get_sales_amount_of_code($user_kpi_code->pt_power_oil_code,$from_date,$to_date);
        return $total;  
    }


    private function get_sales_amount_of_code($code,$from_date,$to_date){
        $q = DB::connection('MotorSparePartsMirror')
             ->select("exec sp_FieldForceSales '$code', '$from_date', '$to_date'");
        if(count($q)){
            return $q[0]->SalesAmount ? $q[0]->SalesAmount : 0;
        }else{
            return 0;
        }

    }


    public function given_month_job_done($user_id,$date){
        $job_cards = JobCard::select('service_masters.code',DB::raw('COUNT(*) as count'))
                        ->join('job_card_details','job_card_details.job_card_id','job_cards.id')
                        ->join('service_types','service_types.id','job_cards.service_type_id')
                        ->join('service_masters','service_masters.id','service_types.service_master_id')
                        ->whereMonth('job_cards.service_date',date('m',strtotime($date)))
                        ->whereYear('job_cards.service_date',date('Y',strtotime($date)))
                        ->where('job_card_details.user_id',$user_id)
                        ->groupby('service_masters.code')
                        ->get();
        $post_warranty_service = 0;
        $warranty_service = 0;
  
  
        foreach($job_cards as $job_card){
          if($job_card->code == 'post_warranty_service') $post_warranty_service = $job_card->count ? $job_card->count : 0 ;
          if($job_card->code == 'warranty_service') $warranty_service = $job_card->count ? $job_card->count : 0 ;
        }
  
        return ['warranty_service'=>(int)$warranty_service,
                'post_warranty_service'=>(int)$post_warranty_service,
                'total'=>(int)$warranty_service + (int)$post_warranty_service,
              ];
      }


    private function given_month_job($date){
       return JobCard::select('users.id as user_id','service_masters.code as service_master_code',DB::raw('COUNT(*) as count'))
                        ->join('job_card_details','job_card_details.job_card_id','job_cards.id')
                        ->join('service_types','service_types.id','job_cards.service_type_id')
                        ->join('service_masters','service_masters.id','service_types.service_master_id')
                        ->whereMonth('job_cards.service_date',date('m',strtotime($date)))
                        ->whereYear('job_cards.service_date',date('Y',strtotime($date)))
                        //->where('job_card_details.user_id',$user_id)
                        ->join('users','users.id','job_card_details.user_id')
                        ->groupby('users.id','service_masters.code')
                        ->get();
        
        // $post_warranty_service = 0;
        // $warranty_service = 0;
  
  
        // foreach($job_cards as $job_card){
        //   if($job_card->code == 'post_warranty_service') $post_warranty_service = $job_card->count ? $job_card->count : 0 ;
        //   if($job_card->code == 'warranty_service') $warranty_service = $job_card->count ? $job_card->count : 0 ;
        // }
  
        // return ['warranty_service'=>(int)$warranty_service,
        //         'post_warranty_service'=>(int)$post_warranty_service,
        //         'total'=>(int)$warranty_service + (int)$post_warranty_service,
        //       ];
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


    public function delete_all_processed_kpi($date){
        $kpia_incentives =  KpiaIncentive::join('kpias','kpias.id','kpia_incentives.kpia_id')->delete();
        $user_kpis = Kpia::whereMonth('date',date('m',strtotime($date)))->delete();
        //$kpia_incentives = KpiaIncentive::get();
        // $user_kpis = Kpia::whereMonth('date',date('m',strtotime($date)))->get();
        // $kpia_incentives = KpiaIncentive::all();

       // foreach($kpia_incentives as $kpia_incentive){
        //     $kpia_incentive->delete();
        // }

        // foreach($user_kpis as $user_kpi){
        //     $user_kpi->delete();
        // }
    }


}