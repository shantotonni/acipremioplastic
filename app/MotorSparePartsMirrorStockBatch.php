<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotorSparePartsMirrorStockBatch extends Model
{
    protected $connection= 'MotorSparePartsMirror';

    protected $table = 'StockBatch';

    public function depot(){
        return $this->belongsTo('App\MotorSparePartsMirrorDepot','DepotCode','DepotCode');
    }
}
