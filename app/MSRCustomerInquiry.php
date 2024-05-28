<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MSRCustomerInquiry extends Model
{
    protected $connection = 'dbmotors';

    protected $table = 'MSRCustomerInquiry';

    public function user(){
        return $this->belongsTo(Usermanager::class,'EntryBy','UserName');
    }
}
