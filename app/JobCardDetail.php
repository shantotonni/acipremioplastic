<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCardDetail extends Model
{
    protected $fillable=["job_card_id","user_id"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function job_card(){
      return $this->belongsTo('App\JobCard');
    }
    public function user(){
      return $this->belongsTo('App\User');
    }

}
