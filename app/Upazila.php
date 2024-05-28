<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    protected $table = 'upazilas';

    public function district(){
        return $this->belongsTo('App\District');
    }
}
