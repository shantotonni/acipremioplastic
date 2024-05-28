<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotorSparePartsMirrorProduct extends Model
{
    protected $connection= 'MotorSparePartsMirror';

    protected $table = 'Product';

    public function stock(){
        return $this->hasMany('App\MotorSparePartsMirrorStockBatch','ProductCode','ProductCode');
    }
}
