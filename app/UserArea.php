<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserArea extends Model
{
    protected $table = 'user_areas';
    protected $guarded=[];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function area(){
      return $this->belongsTo('App\Area');
    }
    public function user(){
      return $this->belongsTo('App\User');
    }

}
