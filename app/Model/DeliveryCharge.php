<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DeliveryCharge extends Model
{
    protected $table = 'DeliveryCharge';

    protected $primaryKey = 'DeliveryChargeId';

    public $timestamps = false;
}
