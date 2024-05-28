<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InquiryProduct extends Model
{
    protected $table = 'Product';

    protected $connection = 'dbmotors';
}
