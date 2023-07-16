<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GuestUser extends Model
{
    protected $table = 'guest_users';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
