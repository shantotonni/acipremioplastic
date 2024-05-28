<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerToken extends Model
{
    protected $fillable=["token","customer_id","firebase_token"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function customer(){
        return $this->belongsTo('App\Customer');
    }
}
