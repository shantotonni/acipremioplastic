<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OperatorInformation extends Model
{
    protected $table = 'operator_informations';

    public function area_name(){
        return $this->belongsTo('App\Area','area','id');
    }

    public function district_name(){
        return $this->belongsTo('App\District','district','id');
    }

}
