<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KpiaIncentive extends Model
{
    protected $fillable=["kpia_id","incentive_factor_id","multiplier","tractor","nmpt","tractor_and_nmpt"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function kpia(){
      return $this->belongsTo('App\Kpium');
    }
    public function incentive_factor(){
      return $this->belongsTo('App\IncentiveFactor');
    }

}
