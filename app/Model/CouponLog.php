<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CouponLog extends Model
{
    protected $table = 'CouponLog';

    protected $primaryKey = 'CouponID';

    protected $guarded = [];

    public $timestamps = false;

    public function user(){

        return $this->belongsTo('App\User', 'CustomerID','CustomerID');
    }
}
