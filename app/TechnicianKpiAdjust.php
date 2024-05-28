<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechnicianKpiAdjust extends Model
{
    protected $fillable=["date","user_id","service_ratio_ws_actual","service_ratio_pws_actual","satisfaction_index_six_actual","satisfaction_index_six_target","satisfaction_index_csi_actual","satisfaction_index_csi_target"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function user(){
      return $this->belongsTo('App\User');
    }

}
