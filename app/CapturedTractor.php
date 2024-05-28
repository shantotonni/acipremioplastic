<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CapturedTractor extends Model
{
    protected $table = "captured_tractors";
    protected $primaryKey = 'id';
    public $timestamps = false;
}
