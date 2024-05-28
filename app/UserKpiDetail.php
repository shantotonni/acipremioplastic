<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserKpiDetail extends Model
{
    protected $fillable=["user_kpi_id","kpi_topic_id","kpi_group_id","target","actual","weight","score","f_score"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function user_kpi(){
      return $this->belongsTo('App\UserKpi');
    }
    public function kpi_topic(){
      return $this->belongsTo('App\KpiTopic');
    }

}
