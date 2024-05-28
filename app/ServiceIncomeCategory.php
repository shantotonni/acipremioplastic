<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceIncomeCategory extends Model
{
    protected $table = 'service_income_category';

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
