<?php

namespace App\Exports;

use App\OperatorInformation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DateTime;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class OperatorInfoExport implements FromCollection, WithHeadings, ShouldAutoSize,WithEvents
{
    protected $f_date;
    protected $t_date;
    protected $mobile;

    function __construct($request) {
        $this->f_date = $request->fromDate;
        $this->t_date = $request->toDate;
        $this->mobile = $request->m_mobile;
    }

    public function collection()
    {
        $from_date = date('Y-m-d',strtotime($this->f_date));
        $to_date = date('Y-m-d',strtotime($this->t_date));
        $mobile = $this->mobile;


        $operators = OperatorInformation::query()->with('area_name', 'district_name');

        if($mobile){
            $operators = $operators->where('mobile',$mobile);
        }
        $operators = $operators->whereDate('training_date',">=",$from_date)
                ->whereDate('training_date',"<=",$to_date)
                ->orderBy('id','Desc')
                ->get();

        $result = [];

        foreach ($operators as $service){

            $result[] = [
                'operator_name' =>$service->operator_name,
                'village' =>$service->village,
                'post_office' =>$service->post_office,
                'police_station' =>$service->police_station,
                'area' =>$service->area_name->name,
                'district' =>$service->district_name->name,
                'mobile' =>$service->mobile,
                'training_level' =>$service->training_level,
                'training_date' =>$service->training_date,
                'training_venue' =>$service->training_venue,
                'total_training_days' =>$service->total_training_days,
                'operating_experience' =>$service->operating_experience,
                'education' =>$service->education,
                'nid_no' =>$service->nid_no,
                'created_at' =>$service->created_at
            ];
        }

        return collect($result);
    }

    public function headings(): array
    {
        return [
            'operator_name',
            'village',
            'post_office',
            'police_station',
            'area',
            'district',
            'mobile',
            'training_level',
            'training_date',
            'training_venue',
            'total_training_days',
            'operating_experience',
            'education',
            'nid_no',
            'created_at'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:AO1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }
}
