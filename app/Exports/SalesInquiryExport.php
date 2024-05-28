<?php

namespace App\Exports;

use App\SaleInquiry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class SalesInquiryExport implements FromCollection, WithHeadings
{
   
        public function collection()
    {

        $sales_inquiry = SaleInquiry::orderBy('id','asc')
            ->with(['upazilla','inquiryType','product','useType','implement','visitResult','occupation','ssr','customer_inquiry','customer_inquiry.user'])
            ->get();

        $result = [];

        foreach ($sales_inquiry as $inquiry){

            $result[] = [
                'id' =>$inquiry->id,
                'village_name' =>$inquiry->village_name,
                'inquiry_name' =>$inquiry->inquiry_name,
                'inquiry_mobile'=>$inquiry->inquiry_mobile,
                'MotorsMSRCode' =>$inquiry->MotorsMSRCode,
                'upazilla_name' =>isset($inquiry->upazilla) ? $inquiry->upazilla->name : '',
                'InquiryTypeName'=>isset($inquiry->inquiryType) ? $inquiry->inquiryType->InquiryTypeName : '',
                'SSR_name' =>isset($inquiry->ssr) ? $inquiry->ssr->name : '',
                'SSR_mobile' =>isset($inquiry->ssr) ? $inquiry->ssr->mobile : '',
                'ProductCode' =>$inquiry->ProductCode,
                'product_name' =>isset($inquiry->product) ? $inquiry->product->ProductName : '',
                'useTypeName' =>isset($inquiry->useType) ? $inquiry->useType->UseTypeName:'',
                'implementName' =>isset($inquiry->implement) ? $inquiry->implement->ImplementName : '',
                'visitResultName' =>isset($inquiry->visitResult) ? $inquiry->visitResult->VisitResultName : '',
                'occupationName' =>isset($inquiry->occupation) ? $inquiry->occupation->OccupationName : '',
                'customer_brand_model' =>$inquiry->customer_brand_model,
                'inquirytype' =>$inquiry->inquirytype,
                'reference_no' =>$inquiry->reference_no,
                'created_at' =>$inquiry->created_at,
                'EntryBy' =>isset($inquiry->customer_inquiry) ? $inquiry->customer_inquiry->EntryBy:'',
                'Name' =>isset($inquiry->customer_inquiry->user) ? $inquiry->customer_inquiry->user->Name : '',
                'Status' =>isset($inquiry->inquiry_last_status->visit_result) ? $inquiry->inquiry_last_status->visit_result->VisitResultName : 'Unsold',
            ];
        }

        return collect($result);
    }

    public function headings(): array
    {
        return [
            'Id',
            'Village name',
            'Name',
            'Mobile',
            'MotorsMSRCode',
            'Upazilla name',
            'Inquiry Type Name',
            'SSR name',
            'SSR mobile',
            'Product Code',
            'Product name',
            'Use Type Name',
            'Implement Name',
            'Visit Result Name',
            'Occupation Name',
            'Customer brand model',
            'Inquiry Type' ,
            'Reference no',
            'Created Date',
            'EntryBy',
            'Name',
            'Status',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }

   
}
