<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'Offer';
    public $timestamps = false;
    protected $primaryKey = 'ID';

    public function offer_products()
    {
        return $this->hasMany(OfferProduct::class,'OfferID','ID');
    }
}
