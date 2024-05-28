<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SalesInquiryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'sales_inquiry'=>$this->collection->transform(function ($sales){
                return [
                    'id' => $sales->id,
                    'MotorsMSRCode' => $sales->MotorsMSRCode,
                    'ProductCode' => $sales->ProductCode,
                    'reference_no' => $sales->reference_no,
                    'village_name' => $sales->village_name,
                    'customer_brand_model' => $sales->customer_brand_model,
                    'quantity' => $sales->quantity,
                    'inquiry_name' => $sales->inquiry_name,
                    'inquiry_mobile' => $sales->inquiry_mobile,
                    'inquirytype' => $sales->inquirytype,
                    'next_visit_date' => $sales->next_visit_date,
                    'expected_delivery_date' => $sales->expected_delivery_date,
                    'InquiryTypeId' =>$sales->InquiryTypeId,
                    'InquiryTypeName' =>isset($sales->inquiryType) ? $sales->inquiryType->InquiryTypeName:'',
                    'UseTypeId' =>$sales->UseTypeId,
                    'UseTypeName' =>isset($sales->useType) ? $sales->useType->UseTypeName:'',
                    'ImplementId' =>$sales->ImplementId,
                    'ImplementName' =>isset($sales->implement) ? $sales->implement->ImplementName:'',
                    'VisitResultId' =>$sales->VisitResultId,
                    'VisitResultName' =>isset($sales->visitResult) ? $sales->visitResult->VisitResultName:'',
                    'OccupationId' =>$sales->OccupationId,
                    'OccupationName' =>isset($sales->occupation) ? $sales->occupation->OccupationName:'',
                    'created_at' => $sales->created_at,
                    'updated_at' => $sales->updated_at,
                    'ssr' => $sales->ssr_id,
                    'ssr_name' => isset($sales->ssr) ? $sales->ssr->name: '',
                ];
            })
        ];
    }
}
