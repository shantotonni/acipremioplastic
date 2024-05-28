<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCard extends Model{
    protected $guarded = [];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function territory(){
      return $this->belongsTo('App\Territory');
    }
    public function area(){
      return $this->belongsTo('App\Area','area_id','id');
    }
    public function engineer(){
      return $this->belongsTo('App\User','engineer_id','id');
    }
    public function technitian(){
      return $this->belongsTo('App\User','technitian_id','id');
    }
    public function participant(){
      return $this->belongsTo('App\User');
    }
    public function product(){
      return $this->belongsTo('App\Product');
    }
    public function call_type(){
      return $this->belongsTo('App\CallType');
    }
    public function service_type(){
      return $this->belongsTo('App\ServiceType','service_type_id','id');
    }
    public function approver(){
      return $this->belongsTo('App\User');
    }
    public function customer(){
        return $this->belongsTo('App\Customer','customer_id','id');
    }
    public function section(){
        return $this->belongsTo('App\Section','section_id','id');
    }
    public function model(){
        return $this->belongsTo('App\ProductModel');
    }
    public function district(){
        return $this->belongsTo('App\District');
    }
    public function upazila(){
        return $this->belongsTo('App\Upazila');
    }
    public function service_income_details(){
        return $this->hasMany('App\ServiceIncomeDetails','job_card_id','id');
    }
    public function service_income_amount(){
        return $this->hasMany('App\ServiceIncomeAmount','job_card_id','id');
    }
    public function image(){
        return $this->hasMany('App\ChassisImage','job_card_id','id');
    }



}
