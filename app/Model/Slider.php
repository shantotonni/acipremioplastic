<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'Banner';

    public $timestamps = false;

    protected $primaryKey = 'BannerID';
}
