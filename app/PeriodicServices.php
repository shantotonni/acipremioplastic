<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeriodicServices extends Model
{
    protected $table = 'periodic_services';
    protected $guarded = [];
    public $timeStamps = false;
}
