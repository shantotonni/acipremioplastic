<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserKpi extends Model{
    protected $fillable=["date","user_id","kpi_type_id","total_kpi_target","total_kpi_ach"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function user(){
      return $this->belongsTo('App\User');
    }
    public function kpi_type(){
      return $this->belongsTo('App\KpiType');
    }

    public function user_kpi_details(){
      return $this->hasMany('App\UserKpiDetail');
    }

}
