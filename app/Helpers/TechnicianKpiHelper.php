<?php 
namespace App\Helpers;

use App\KpiTopic;
use App\Target;
use App\User;
use App\UserKpi;
use App\UserKpiDetail;
use App\BaseLine;
use App\UserKpiBaseLine;
use DB;

class TechnicianKpiHelper{

    public function process_technician_kpi($date){
        $users = User::select('users.id as id','targets.id as target_id',
                       'targets.warranty_master_total','targets.post_warranty_master_total',
                       'targets.service_income','targets.tractor_spare_parts_lubricants',
                       'targets.nm_pt_spare_parts_lubricants'
                        )
                       ->join('targets','targets.technitian_id','users.id')
                       ->where('users.username','C33310')
                       ->whereMonth('targets.date',date('m',strtotime($date)))
                       ->get();
              
        $kpi_topics = KpiTopic::where('kpi_type_id',3)
                      ->orderBy('sl',"ASC")->get();

        foreach($users as $user){
            $user_kpi = $this->create_user_kpi($user->id,$date);
            $total_kpi_ach    = 0;
            $total_incentive_amount = 0;

            foreach($kpi_topics as $kpi_topic){

                if($kpi_topic->id == 1){ //warranty
                   $target = $user->warranty_master_total;
                   $actual = $this->get_technician_warranty_actual($user->id,$date);
                   $user_kpi_detail = $this->create_user_kpi_detail($user_kpi,$kpi_topic,$target,$actual);
                 
                   $total_kpi_ach    += $user_kpi_detail->f_score;
                }
                if($kpi_topic->id == 2){ //post Warranty
                    $target = $user->post_warranty_master_total;
                    $actual = $this->get_technician_post_warranty_actual($user->id,$date);
                    $user_kpi_detail = $this->create_user_kpi_detail($user_kpi,$kpi_topic,$target,$actual);
                   
                    $total_kpi_ach    += $user_kpi_detail->f_score;
                }

                if($kpi_topic->id == 3){  // six hours
                    $s = $this->get_monthly_six_hour_ratio($user->id,$date);
                    $target = $s['Total'];
                    $actual = $s['Y'];
                    $user_kpi_detail = $this->create_user_kpi_detail($user_kpi,$kpi_topic,$target,$actual);
                 
                    $total_kpi_ach    += $user_kpi_detail->f_score;
                }
                if($kpi_topic->id == 4){ //csi
                    $s = $this->get_monthly_csi($user->id,$date);
                    $target = $s['OutOf'];
                    $actual = $s['Marks'];
                    $user_kpi_detail = $this->create_user_kpi_detail($user_kpi,$kpi_topic,$target,$actual);
                    
                    $total_kpi_ach    += $user_kpi_detail->f_score;
                }
                if($kpi_topic->id == 5){ // service income
                    $target = $user->service_income;
                    $actual = $this->get_technician_service_income($user->id,$date);
                    $user_kpi_detail = $this->create_user_kpi_detail($user_kpi,$kpi_topic,$target,$actual);
                 
                    $total_kpi_ach    += $user_kpi_detail->f_score;
                }

                if($kpi_topic->id == 6){ 
                    $target = $user->tractor_spare_parts_lubricants;
                    $actual = $this->get_technician_tractor_sales_actual($user->id,$date);
                    $user_kpi_detail = $this->create_user_kpi_detail($user_kpi,$kpi_topic,$target,$actual);
                }

                if($kpi_topic->id == 7){ 
                    $target = $user->nm_pt_spare_parts_lubricants;
                    $actual = $this->get_technician_nm_pt_sales_actual($user->id,$date);
                    $user_kpi_detail = $this->create_user_kpi_detail($user_kpi,$kpi_topic,$target,$actual);
                }

                if($kpi_topic->id == 8){ 
                    $target = $user->tractor_spare_parts_lubricants + $user->nm_pt_spare_parts_lubricants;
                    $actual = $this->get_technician_tractor_sales_actual($user->id,$date) 
                              + $this->get_technician_nm_pt_sales_actual($user->id,$date);
                    $user_kpi_detail = $this->create_user_kpi_detail($user_kpi,$kpi_topic,$target,$actual);
                   
                    $total_kpi_ach    += $user_kpi_detail->f_score;
                }

                $user_kpi_base_line = $this->create_user_kpi_baseline($user_kpi,$user_kpi_detail);


            }// topic foreach end

            $user_kpi->total_kpi_ach = $total_kpi_ach;
            $user_kpi->save();
        }// user foreach end

        dd('done technician kpi');

    }

    private function get_technician_target($user_id,$date){

    }

    private function get_technician_warranty_actual($user_id,$date){
        return 6;
    }

    private function get_technician_post_warranty_actual($user_id,$date){
       return 9;
    }

    private function get_technician_tractor_sales_actual($user_id,$date){
        return 120;
    }

    private function get_technician_nm_pt_sales_actual($user_id,$date){
        return 140;
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



    private function get_technician_service_income($user_id,$date){
          return 3220;
    }

    private function create_user_kpi($user_id,$date){
        $user_kpi= new UserKpi();
        $user_kpi->date=date("Y-m-d",strtotime($date));
        $user_kpi->user_id=$user_id;
        $user_kpi->kpi_type_id=3;
        $user_kpi->total_kpi_target=100;
        $user_kpi->total_kpi_ach=0;
        $user_kpi->save();
        return $user_kpi;
    }

    public function create_user_kpi_detail($user_kpi,$kpi_topic,$target,$actual){
        $score = $target > 0 ? number_format(($actual/$target)*$kpi_topic->weight,2) : 0;

        $user_kpi_detail= new UserKpiDetail();
        $user_kpi_detail->user_kpi_id=$user_kpi->id;
        $user_kpi_detail->kpi_topic_id=$kpi_topic->id;
        $user_kpi_detail->kpi_group_id=$kpi_topic->kpi_group_id;
        $user_kpi_detail->target=$target;
        $user_kpi_detail->actual=$actual;
        $user_kpi_detail->weight=$kpi_topic->weight;

        $user_kpi_detail->score = $score;    
        if($score > $kpi_topic->weight){
            $user_kpi_detail->f_score= $kpi_topic->weight;
        }else{
            $user_kpi_detail->f_score= $score;
        }
        
        $user_kpi_detail->save();
        return $user_kpi_detail;
        
    }

    private function create_user_kpi_baseline($user_kpi,$user_kpi_detail){
        if($user_kpi_base_line = $this->isExistKpiBaseLines($user_kpi->id,$user_kpi_detail->kpi_group_id)){

        }else{
            $user_kpi_base_line= new UserKpiBaseLine;
        }

        $user_kpi_base_line->user_kpi_id=$user_kpi->id;
        $user_kpi_base_line->kpi_group_id=$user_kpi_detail->kpi_group_id;
        $user_kpi_base_line->amount=$this->get_base_line_incentive(3,$user_kpi_detail->kpi_group_id);
        $user_kpi_base_line->save();

        return $user_kpi_base_line;
    }


    public function get_base_line_incentive($kpi_type_id,$kpi_group_id){
        return 1500;
        // $baseline = BaseLine::where('kpi_type_id',$kpi_type_id)
        //             ->where('kpi_group_id',$kpi_group_id)
        //             ->first();
        // return $baseline ? $baseline->amount : 0;
    }

    private function isExistKpiBaseLines($user_kpi_id,$kpi_group_id){
       return UserKpiBaseLine::where('user_kpi_id',$user_kpi_id)
                               ->where('kpi_group_id',$kpi_group_id)
                               ->first();
    }

    public function delete_all_processed_kpi($date){
        $kpi_base_lines = UserKpiBaseLine::all();
        $user_kpi_details = UserKpiDetail::select('user_kpi_details.id as id')
                            ->join('user_kpis','user_kpis.id','user_kpi_details.user_kpi_id')
                            ->where('user_kpis.date',$date)->get();
        $user_kpis = UserKpi::where('date',$date)->get();
        foreach($user_kpi_details as $user_kpi_detail){
            $user_kpi_detail->delete();
        }


        foreach($kpi_base_lines as $kpi_base_line){
            $kpi_base_line->delete();
        }

        foreach($user_kpis as $user_kpi){
            $user_kpi->delete();
        }

        return 1;
    }

}