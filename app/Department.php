<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable=["business_unit_id","name","code"];

    public function business_unit(){
      return $this->belongsTo('App\BusinessUnit');
    }

}
