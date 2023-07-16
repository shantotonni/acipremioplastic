<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'Invoice';

    protected $primaryKey = 'InvoiceNo';

    public $timestamps = false;

    public function invoiceDetail(){

        return $this->hasMany('App\Model\InvoiceDetail', 'InvoiceNo','InvoiceNo');
    }

    public function invoiceStatus(){

        return $this->belongsTo('App\Model\InvoiceStatus', 'InvStatusID','InvStatusID');
    }

    public function customer(){

        return $this->belongsTo('App\User', 'CustomerID','CustomerID');
    }
    public function coupon(){

        return $this->hasOne('App\Model\CouponLog', 'CouponID','CouponID');
    }
}
