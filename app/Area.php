<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable=["name","code"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function user_area(){
        return $this->hasOne('App\UserArea');
    }

    public function territories(){
        return $this->hasMany('App\Territory');
    }

    public function engineer(){
        return $this->hasOne('App\UserArea');
    }
}
