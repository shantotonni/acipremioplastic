<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeriodicServiceHistories extends Model
{
    protected $table = 'periodic_service_histories';
    protected $guarded = [];

    public function periodic_service()
    {
        return $this->belongsTo(PeriodicServices::class,'periodic_service_id','id');
    }
}
