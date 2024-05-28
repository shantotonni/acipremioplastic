<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleInquiry extends Model
{
    protected $table = 'sales_inquiry';

    public function upazilla(){
        return $this->belongsTo('App\Upazila','MotorsMSRCode','MotorsMSRCode');
    }
    public function inquiryType(){
        return $this->belongsTo('App\InquiryType','InquiryTypeId','InquiryTypeId');
    }
    public function product(){
        return $this->belongsTo('App\InquiryProduct','ProductCode','ProductCode');
    }
    public function useType(){
        return $this->belongsTo('App\UseType','UseTypeId','UseTypeId');
    }
    public function implement(){
        return $this->belongsTo('App\Implement','ImplementId','ImplementId');
    }
    public function visitResult(){
        return $this->belongsTo('App\VisitResult','VisitResultId','VisitResultId');
    }
    public function occupation(){
        return $this->belongsTo('App\Occupation','OccupationId','OccupationId');
    }
    public function ssr(){
        return $this->belongsTo('App\User','ssr_id','id');
    }
    public function inquiry_last_status(){
        return $this->hasOne(CustomerInquiryLastStatus::class,'InquiryId','reference_no');
    }

    public function customer_inquiry(){
        return $this->belongsTo(MSRCustomerInquiry::class,'reference_no','InquiryId');
    }
}
