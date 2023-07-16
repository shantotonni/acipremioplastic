<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $table = 'CustomerAddress';

    protected $primaryKey = 'AddressID';

    public $timestamps = false;

    public function addressType(){

        return $this->belongsTo('App\Model\AddressType', 'AddressTypeID','AddressTypeID');
    }

    public function user(){

        return $this->belongsTo('App\User', 'CustomerID','CustomerID');
    }
}
