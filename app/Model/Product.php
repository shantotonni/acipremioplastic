<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'Product';

    public $timestamps = false;

    protected $primaryKey = 'ProductCode';

    public function productImage() {
        return $this->hasMany('App\Model\ProductImage','ProductID','ProductCode');
    }

    public function review() {
        return $this->hasMany('App\Model\ReviewRating','ProductId','ProductCode')->where('Approved',1)->take(5)->latest('CreatedAt');
    }

    public function average() {
        return $this->hasMany('App\Model\ReviewRating','ProductId','ProductCode')->where('Approved',1);
    }

    public function category() {
        return $this->belongsTo('App\Model\Category','CategoryId','CategoryId')->where('CategoryStatus','Y');
    }

    public function stock() {
        return $this->hasOne('App\Model\Stock','ProductCode','ProductCode');
    }
}
