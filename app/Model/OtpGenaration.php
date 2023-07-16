<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OtpGenaration extends Model
{
    protected $table = 'OTPGeneration';

    protected $primaryKey = 'OTPId';

    public $timestamps = false;
}
