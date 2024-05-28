<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';

    public function area(){
        return $this->belongsTo('App\Area');
    }

}
