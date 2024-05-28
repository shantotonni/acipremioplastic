<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DateTime;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class PeriodicServiceReportExport implements FromCollection, WithHeadings, ShouldAutoSize,WithEvents
{
    protected $f_date;
    protected $t_date;
    protected $status;

    function __construct($request) {
        $this->f_date = $request->dateFrom;
        $this->t_date = $request->dateTo;
        $this->status = $request->status;
    }

    public function collection()
    {
        $query = DB::table('periodic_service_histories as psh1')
            ->join('areas','psh1.area_id','=','areas.id')
            ->whereRaw("periodic_service_id = (SELECT MAX(psh2.periodic_service_id) FROM periodic_service_histories AS psh2 WHERE psh1.chassisno = psh2.chassisno) ");
        if ($this->f_date != '' && $this->t_date != '') {
            $query->whereBetween('service_date',[Carbon::parse($this->f_date)->format('Y-m-d'),Carbon::parse($this->t_date)->format('Y-m-d')]);
        }
        if ($this->status === 'onTime') {
            $query->whereRaw("psh1.periodic_service_id > '1' AND FORMAT(psh1.service_date,'yyyy-MM') = FORMAT(psh1.previous_service_date,'yyyy-MM')");
        }
        elseif ($this->status === 'early') {
            $query->whereRaw("psh1.periodic_service_id > '1' AND FORMAT(psh1.service_date,'yyyy-MM') < FORMAT(psh1.previous_service_date,'yyyy-MM')");
        }
        elseif ($this->status === 'delay') {
            $query->whereRaw("psh1.periodic_service_id > '1' AND FORMAT(psh1.service_date,'yyyy-MM-dd') > FORMAT(psh1.previous_service_date,'yyyy-MM-dd')");
        }
        $query->select('psh1.customer_code','psh1.customer_name','psh1.chassisno',
                DB::raw(
                    "CAST(
                        CASE 
                            WHEN psh1.periodic_service_id = '1'
                                THEN 'First Service'
                                ELSE CONVERT(varchar(50),FORMAT(psh1.previous_service_date,'dd-MM-yyyy'))
                            END as varchar(50)
                    ) as pre_date"
                ),
                DB::raw("FORMAT(psh1.service_date,'dd-MM-yyyy') as service_date"),
                DB::raw(
                    "CAST(
                        CASE
                            WHEN psh1.periodic_service_id > '1' AND FORMAT(psh1.service_date,'yyyy-MM') = FORMAT(psh1.previous_service_date,'yyyy-MM')
                                THEN 'ON-TIME'
                                WHEN psh1.periodic_service_id > '1' AND FORMAT(psh1.service_date,'yyyy-MM') < FORMAT(psh1.previous_service_date,'yyyy-MM')
                                THEN 'EARLY'
                                WHEN psh1.periodic_service_id > '1' AND FORMAT(psh1.service_date,'yyyy-MM-dd') > FORMAT(psh1.previous_service_date,'yyyy-MM-dd')
                                THEN 'DELAY'
                                WHEN psh1.periodic_service_id = '1'
                                THEN 'FIRST SERVICE'
                            END as varchar(50)
                    ) as status"
                ),'areas.name as area_name'
            );
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Customer Code',
            'Customer Name',
            'Chassis No',
            'Expected Service Date',
            'Service Date',
            'Status',
            'Area',
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
