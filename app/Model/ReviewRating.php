<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ReviewRating extends Model
{
    protected $table = 'Review';

    protected $primaryKey = 'ReviewId';

    public $timestamps = false;
}
