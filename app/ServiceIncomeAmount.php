<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceIncomeAmount extends Model
{
    protected $table = 'service_income_amount';

    public function job_card(){
        return $this->belongsTo('App\JobCard','job_card_id','id');
    }
}
