<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceCenter extends Model
{
    protected $table = 'service_centers';

    public function area(){
        return $this->hasOne('App\Area','id','area_id');
    }
}
