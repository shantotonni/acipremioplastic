<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public function orderProduct(){
        return $this->hasMany('App\OrderProduct','order_id','id');
    }

    public function customer(){
        return $this->belongsTo('App\Customer','customer_id','id');
    }
}
