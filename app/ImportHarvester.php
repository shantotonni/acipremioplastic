<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportHarvester extends Model
{
    protected $table = 'chassis_number_wise_harvester_info';

    protected $fillable = ['customer_name','product','engine_no','chesis'];

}
