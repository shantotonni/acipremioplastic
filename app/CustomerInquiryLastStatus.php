<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerInquiryLastStatus extends Model
{
    protected $connection = 'dbmotors';

    protected $table = 'MSRCustomerInquiryLastStatus';

    public function visit_result(){
        return $this->belongsTo(VisitResult::class,'VisitResultId','VisitResultId');
    }
}
