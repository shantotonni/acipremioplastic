<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesManagerInfo extends Model
{
    protected $table = 'sales_manager_info';

    public function area(){
        return $this->belongsTo('App\Area','area_id','id');
    }
}
