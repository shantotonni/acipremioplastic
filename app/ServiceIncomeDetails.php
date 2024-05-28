<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceIncomeDetails extends Model
{
    protected $table = 'service_income_details';

    public function service_category(){
        return $this->belongsTo('App\ServiceIncomeCategory','service_category_id','id');
    }
}
