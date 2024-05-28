<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Territory extends Model
{
    protected $fillable=["area_id","name","code"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function area(){
      return $this->belongsTo('App\Area');
    }
    public function user_territory()
    {
      return $this->belongsTo(UserTerritory::class, 'id','territory_id');
    }

}
