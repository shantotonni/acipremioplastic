<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DateTime;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class PeriodicServiceExport implements FromCollection, WithHeadings, ShouldAutoSize,WithEvents
{
    protected $f_date;
    protected $t_date;

    function __construct($request) {
        $this->f_date = $request->fromDate;
        $this->t_date = $request->toDate;
        $this->StatusCode = $request->StatusCode;
    }

    public function collection()
    {
        $from_date = date('Y-m-d',strtotime($this->f_date));
        $to_date = date('Y-m-d',strtotime($this->t_date));
        $StatusCode = $this->StatusCode;

        if($StatusCode=='Pending'){
            $string = "AND next_service_date >= '".date('Y-m-d')."'";
        }
        if($StatusCode=='Expired'){
            $string = "AND next_service_date < '".date('Y-m-d')."'";
        }

        if($StatusCode==""){
            $string = "";
        }

        $services = DB::select("SELECT 
            *
          From (
          select 
          row_number() over(partition by i.chassisno order by service_date desc) sl,
          i.*, psh.service_date,psh.next_service_date,psh.service_hour,ps.service_hour as ps_hour  from InvoiceCustomerList i
          left  join periodic_service_histories psh on i.ChassisNo=psh.chassisno
          left join periodic_services ps on ps.id = psh.periodic_service_id+1
          where i.ChassisDouble=0 
          ) S where sl = 1 $string AND next_service_date BETWEEN '$from_date' AND '$to_date'
          order by CustomerCode asc");

        $result = [];

        foreach ($services as $service){

            $result[] = [
                'CustomerCode' =>$service->CustomerCode,
                'CustomerName1' =>$service->CustomerName1,
                'Address1' =>$service->Address1,
                'Mobile' =>$service->Mobile,
                'ChassisNo' =>$service->ChassisNo,
                'Model' =>$service->Model,
                'service_date' =>$service->service_date,
                'next_service_date' =>$service->next_service_date,
                'status' =>($service->next_service_date != NULL && $service->next_service_date < \Carbon\Carbon::now()) ? "Expired" : (($service->next_service_date != NULL && $service->next_service_date >= \Carbon\Carbon::now()) ? "Pending" : "")
            
            ];
        }

        return collect($result);
    }

    public function headings(): array
    {
        return [
            'CustomerCode',
            'CustomerName1',
            'Address1',
            'Mobile',
            'ChassisNo',
            'Model',
            'service_date',
            'next_service_date',
            'status'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:AJ1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }
}
