<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    public function area(){
        return $this->belongsTo('App\Area','area_id','id');
    }

    public function model(){
        return $this->belongsTo('App\Product','product_id','id');
    }
    
    public function productModel(){
        return $this->belongsTo('App\ProductModel','model_id','id');
    }

    protected $hidden = [
        'password',
    ];
}
