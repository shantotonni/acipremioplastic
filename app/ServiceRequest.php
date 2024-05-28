<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $table = 'service_requests';

    public function district(){
        return $this->belongsTo('App\District');
    }
    public function upazila(){
        return $this->belongsTo('App\Upazila');
    }
    public function service_type(){
        return $this->belongsTo('App\ServiceType');
    }
    public function area(){
        return $this->belongsTo('App\Area');
    }
    public function customer(){
        return $this->belongsTo('App\Customer');
    }
    public function engineer(){
        return $this->belongsTo('App\User');
    }
    public function technician(){
        return $this->belongsTo('App\User','technitian_id','id');
    }
    public function solver(){
        return $this->belongsTo('App\User');
    }
    public function section(){
        return $this->belongsTo('App\Section');
    }
    public function topic(){
        return $this->belongsTo('App\Topic');
    }
}
