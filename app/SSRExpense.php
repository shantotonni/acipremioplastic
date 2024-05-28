<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SSRExpense extends Model
{
    protected $table = 'ssr_expense';

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
