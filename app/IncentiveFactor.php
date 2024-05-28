<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncentiveFactor extends Model
{
    protected $fillable=["kpi_type_id","name","from","to","multiplication_factor"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function kpi_type(){
      return $this->belongsTo('App\KpiType');
    }

}
