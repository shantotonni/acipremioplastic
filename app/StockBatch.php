<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockBatch extends Model
{
    protected $connection= 'MotorBrInvoiceMirror';

    protected $table = 'StockBatch';
}
