<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserKpiBaseLine extends Model
{
    protected $fillable=["user_kpi_id","kpi_group_id","amount"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function user_kpi(){
      return $this->belongsTo('App\UserKpi');
    }
    public function kpi_group(){
      return $this->belongsTo('App\KpiGroup');
    }

}
