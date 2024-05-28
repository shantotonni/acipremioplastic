<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $table = 'product_model';
    protected $guarded = [];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
