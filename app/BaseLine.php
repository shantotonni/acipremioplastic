<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseLine extends Model
{
    protected $fillable=["kpi_type_id","service_income_base_line","sp_tractor_base_line","sp_nmpt_base_line","sp_tractor_plus_nmpt_base_line"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function kpi_type(){
      return $this->belongsTo('App\KpiType');
    }

}
