<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserKpiCode extends Model{
  
    protected $fillable=["user_id","service_income_code",
                        "tractor_spare_parts_code","tractor_sonalika_lub_code",
                        "tractor_power_oil_code",
                        "nm_spare_parts_code","nm_power_oil_code",
                        "pt_spare_parts_code","pt_power_oil_code"];

    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function user(){
      return $this->belongsTo('App\User');
    }

}
