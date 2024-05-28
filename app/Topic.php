<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = 'topics';

    public function section(){
        return $this->belongsTo('App\Section');
    }
}
