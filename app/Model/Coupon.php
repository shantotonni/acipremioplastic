<?php

namespace App\Model;

use App\Http\Controllers\CommonHelper;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'Coupon';
    protected $primaryKey = 'CouponID';
    public $timestamps = false;

    public function couponProduct() {
        return $this->hasMany('App\Model\CouponProduct','CouponID','CouponID');
    }

    public function scopePermitted($query)
    {
        $projectIds = array_column(CommonHelper::getUserProject(),'ProjectID');
        return $query->whereIn('ProjectID', $projectIds);
    }
}
