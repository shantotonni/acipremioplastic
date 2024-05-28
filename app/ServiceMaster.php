<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceMaster extends Model
{
    protected $fillable=["name","code"];
    protected $dateFormat = 'Y-m-d H:i:s.v';


}
