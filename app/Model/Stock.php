<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'Stock';
    protected $primaryKey = 'ProductCode';

    public $timestamps = false;

   // protected $primaryKey = 'ProductCode';
}
