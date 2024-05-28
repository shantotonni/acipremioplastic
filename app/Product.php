<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable=["name","name_bn","code"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function sections(){
        return $this->hasMany('App\Section','product_id','id');
    }
    public function model(){
        return $this->hasMany('App\ProductModel','product_id','id');
    }


}
