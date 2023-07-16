<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InvoiceStatus extends Model
{
    protected $table = 'OrderStatus';
    public $timestamps = false;
    protected $primaryKey = 'InvStatusID';
}
