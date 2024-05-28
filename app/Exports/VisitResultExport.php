<?php

namespace App\Exports;

use App\Visit;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VisitResultExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function __construct($request)
    {
        $this->fromDate = date($request->dateFrom);
        $this->toDate = date($request->dateTo);
    }
 
    public function collection()
    {
        $result = [];
        $from_date = $this->fromDate;
        $to_date = $this->toDate;
        
        if($from_date != null && $to_date != null){
            $visits = Visit::where(function ($query) use ($from_date, $to_date) {
                $query->whereDate('created_at', '>=', $from_date);
                $query->whereDate('created_at', '<=', $to_date);
            })->with('upazilla','visit_type','result','user')->latest()->get();
        }else{
            $visits = Visit::with('upazilla','visit_type','result','user')->latest()->get();
        }

        // dd($visits);
        foreach ($visits as $visit) {
            $result[] = [
                'Id' => $visit->id,
                'upazilla_id' => $visit->upazilla->name,
                'visit_type_id' => $visit->visit_type->name,
                'result_id' => $visit->result->name,
                'ssr_id' => $visit->user->name,
                'village_name' => $visit->village_name,
                'purpose' => $visit->purpose,
                'person_name' => $visit->person_name,
                'person_mobile' => $visit->person_mobile,
                'created_at' => date('d-M-Y h:i:s A', strtotime($visit->created_at))
            ];
        }
        // dd($result);
        $visits = collect($result);
        return $visits;
    }
 
    public function headings(): array
    {
        return [
            'Id',
            'upazilla',
            'visit_type',
            'result',
            'ssr',
            'village_name',
            'purpose',
            'person_name',
            'person_mobile',
            'created_at'
        ];
    }

}
