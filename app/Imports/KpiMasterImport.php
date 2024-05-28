<?php

namespace App\Imports;

use App\KpiMaster;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KpiMasterImport implements ToModel,WithStartRow
{
    public function __construct($date) {
        $this->date = $date;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {     
          //dd($row);
            return new KpiMaster([
                'serial' => $row[0],
                'staff_id' => $row[1],
                'name' => $row[2],
                'period' => $this->date,
                'designation' => $row[3],
                'region' => $row[4],
                'territory' => $row[5],
                'warranty_service_target' => $row[6],
                'warranty_service_actual' => $row[7],
                'warranty_service_percentage' => $row[8],
                'post_warranty_service_target' => $row[9],
                'post_warranty_service_actual' => $row[10],
                'post_warranty_service_percentage' => $row[11],
                'csi_percentage' => $row[12],
                'six_hour_percentage' => $row[13],
                'tracking' => $row[14],
                'in_apps' => $row[15],
                'apps_percentage' => $row[16],
                'service_income_target' => $row[17],
                'service_income_actual' => $row[18],
                'service_income_percentage' => $row[19],
                'spare_parts_target' => $row[20],
                'spare_parts_actual' => $row[21],
                'spare_parts_percentage' => $row[22],

                // 'spare_parts_tractor_target' => $row[17],
                // 'spare_parts_tractor_actual' => $row[18],
                // 'spare_parts_tractor_percentage' => $row[19],
                // 'spare_parts_nm_and_pt_target' => $row[20],
                // 'spare_parts_nm_and_pt_actual' => $row[21],
                // 'spare_parts_nm_and_pt_percentage' => $row[22]
            ]);    
    }
}
