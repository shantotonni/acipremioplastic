<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OfferProduct extends Model
{
    protected $table = 'OfferProduct';
    public $timestamps = false;
    protected $primaryKey = 'ID';

    public function products(){

        return $this->belongsTo('App\Model\Product', 'ProductCode','ProductCode');

    }
}
