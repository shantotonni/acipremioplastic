<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = 'order_product';

    public function order(){
        return $this->belongsTo('App\Order');
    }
    public function part(){
        return $this->belongsTo('App\TractorParts','product_id','id');
    }
}
