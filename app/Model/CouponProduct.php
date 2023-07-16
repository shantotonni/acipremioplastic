<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CouponProduct extends Model
{
    protected $table = 'CouponProduct';
    protected $primaryKey = 'CouponProductID';
    public $timestamps = false;

    public function coupon() {
        return $this->belongsTo('App\Model\Coupon','CouponID','CouponID');
    }

    public function product() {
        return $this->belongsTo('App\Model\Product','ProductID','ProductCode')->where('ProductStatus','Y');
    }
}
