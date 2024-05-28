<?php

namespace App\Exports;

use App\ServiseDetails;
use Maatwebsite\Excel\Concerns\FromCollection;

class TractorServiceExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //$tractor_details = ServiseDetails::all();
        //return $tractor_details;

        $tractor_details = ServiseDetails::with('topic','servicing_type')->orderBy('created_at','desc')->get();
        $result = [];
        foreach ($tractor_details as $value){
            $result[] = [
                'topic'=>isset($value->topic) ? $value->topic->name : '',
                'From_hr'=>$value->from_hr,
                'to_hr'=>$value->to_hr,
                'fixed_hr'=>$value->fixed_hr,
                'servicing_type'=>isset($value->servicing_type) ? $value->servicing_type->name: '',
            ];
        }

        return collect($result);
    }
}
