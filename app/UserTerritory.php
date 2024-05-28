<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTerritory extends Model{
    protected $fillable=["territory_id","user_id","supervisor_id"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function territory(){
      return $this->belongsTo('App\Territory');
    }
    public function user(){
      return $this->belongsTo('App\User');
    }

    public function supervisor(){
      return $this->belongsTo('App\User');
    }

}
