<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DealerPoint extends Model
{
    protected $table = 'dealer_centers';

    public function area(){
        return $this->belongsTo('App\Area','area_id','id');
    }
}
