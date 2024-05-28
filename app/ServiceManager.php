<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceManager extends Model
{
    protected $table = 'service_manager';

    public function area(){
        return $this->belongsTo('App\Area','area_id','id');
    }
}
