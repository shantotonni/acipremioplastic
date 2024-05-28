<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiseDetails extends Model
{
    protected $table = 'tractor_service_details';

    public function topic(){
        return $this->belongsTo('App\Topic','topic_id','id');
    }

    public function servicing_type(){
        return $this->belongsTo('App\ServicingType','servicing_type_id','id');
    }
}
