<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KpiTopic extends Model
{
    protected $fillable=["name","kpi_type_id","kpi_group_id","sl","mark","ach_from","ach_to","weight","multiplication_factor"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function kpi_type(){
      return $this->belongsTo('App\KpiType');
    }
    public function kpi_group(){
      return $this->belongsTo('App\KpiGroup');
    }

}
