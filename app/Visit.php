<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $table = 'visits';

    public function upazilla(){
        return $this->belongsTo(Upazila::class,'upazilla_id','id');
    }
    public function visit_type(){
        return $this->belongsTo(VisitType::class,'visit_type_id','id');
    }
    public function result(){
        return $this->belongsTo(Result::class,'result_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'ssr_id','id');
    }
}
