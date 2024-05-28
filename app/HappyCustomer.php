<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HappyCustomer extends Model
{
    protected $table = 'happy_customer';

    public function area(){
        return $this->belongsTo('App\Area','area_id','id');
    }
}
