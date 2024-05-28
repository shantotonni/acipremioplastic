<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KpiGroup extends Model
{
    protected $fillable=["name"];
    protected $dateFormat = 'Y-m-d H:i:s.v';


}
