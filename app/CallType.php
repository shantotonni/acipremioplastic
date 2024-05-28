<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallType extends Model
{
    protected $fillable=["name","name_bn","code"];
    protected $dateFormat = 'Y-m-d H:i:s.v';


}
