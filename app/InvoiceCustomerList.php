<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceCustomerList extends Model
{
    protected $table = "InvoiceCustomerList";
    protected $primaryKey = false;
    public $timestamps = false;

    public function captured_tractor()
    {
        return $this->belongsTo(CapturedTractor::class, 'ChassisNo', 'chassis_no');
    }
}
