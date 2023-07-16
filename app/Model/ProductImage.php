<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'ProductImage';
    protected $primaryKey= 'ProductImageID';
    public $timestamps = false;
}
