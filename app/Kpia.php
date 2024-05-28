<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kpia extends Model
{
    protected $fillable=["date","user_id","kpi_type_id","total_incentive_bonus","total_kpi_mark","total_incentive_amount","service_ratio_ws_target","service_ratio_ws_actual","service_ratio_ws_weight","service_ratio_ws_score","service_ratio_ws_f_score","service_ratio_pws_target","service_ratio_pws_actual","service_ratio_pws_weight","service_ratio_pws_score","service_ratio_pws_f_score","satisfaction_index_six_target","satisfaction_index_six_actual","satisfaction_index_six_weight","satisfaction_index_six_score","satisfaction_index_six_f_score","satisfaction_index_csi_target","satisfaction_index_csi_actual","satisfaction_index_csi_weight","satisfaction_index_csi_score","satisfaction_index_csi_f_score","service_income_target","service_income_actual","service_income_weight","service_income_score","service_income_f_score","report_submission_target","report_submission_actual","report_submission_weight","report_submission_score","report_submission_f_score","app_monitor_target","app_monitor_actual","app_monitor_weight","app_monitor_score","app_monitor_f_score","team_co_target","team_co_actual","team_co_weight","team_co_score","team_co_f_score","service_income_base_line","service_f_score_total","service_f_score_percent","service_income_total_incentive","sp_tractor_target","sp_tractor_actual","sp_tractor_weight","sp_tractor_score","sp_tractor_f_score","sp_tractor_base_line","sp_tractor_f_score_total","sp_tractor_f_score_percent","sp_tractor_total_incentive","sp_nmpt_target","sp_nmpt_actual","sp_nmpt_weight","sp_nmpt_score","sp_nmpt_f_score","sp_nmpt_base_line","sp_nmpt_f_score_total","sp_nmpt_f_score_percent","sp_nmpt_total_incentive","sp_tractor_plus_nmpt_target","sp_tractor_plus_nmpt_actual","sp_tractor_plus_nmpt_weight","sp_tractor_plus_nmpt_score","sp_tractor_plus_nmpt_f_score","sp_tractor_plus_nmpt_base_line","sp_tractor_plus_nmpt_f_score_total","sp_tractor_plus_nmpt_f_score_percent","sp_tractor_plus_nmpt_total_incentive"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function user(){
      return $this->belongsTo('App\User');
    }
    public function kpi_type(){
      return $this->belongsTo('App\KpiType');
    }

    public function kpia_incentives(){
        return $this->hasMany('App\KpiaIncentive','kpia_id');
    }
}
