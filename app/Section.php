<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'sections';

    public function product(){
        return $this->belongsTo('App\Product','product_id','id');
    }
}
