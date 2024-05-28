<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShowroomCentre extends Model
{
    protected $table = 'showroom_centre';

    public function area(){
        return $this->belongsTo('App\Area','area_id','id');
    }
}
