<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicingType extends Model
{
    protected $table = 'servicing_types';
    protected $fillable=["name","code"];
    protected $dateFormat = 'Y-m-d H:i:s.v';
}
