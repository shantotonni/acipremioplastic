<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SSRSalary extends Model
{
    protected $table = 'SSRSalary';

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }
}
