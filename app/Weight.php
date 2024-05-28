<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Weight extends Model
{
    protected $fillable=["kpi_type_id","service_ratio_ws_weight","service_ratio_pws_weight","satisfaction_index_six_weight","satisfaction_index_csi_weight","service_income_weight","report_submission_weight","app_monitor_weight","team_co_weight","sp_tractor_weight","sp_nmpt_weight","sp_tractor_plus_nmpt_weight"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function kpi_type(){
      return $this->belongsTo('App\KpiType');
    }

}
