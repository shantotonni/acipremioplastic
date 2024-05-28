<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model{

    protected $table = 'designations';

    protected $fillable=["name","code",'tractor_parts_base_amount','service_base_amount','nm_parts_base_amount'];
}
