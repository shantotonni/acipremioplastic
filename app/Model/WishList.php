<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    protected $table = 'WishList';

    public $timestamps = false;

    protected $primaryKey = 'WishListID';

    public function product(){

        return $this->belongsTo('App\Model\Product', 'ProductCode','ProductCode');
    }

    public function user(){

        return $this->belongsTo('App\User', 'CustomerID','CustomerID');
    }

}
