<?php

namespace App\Imports;

use App\ImportHarvester;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ChassisNumberWiseHarvesterImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {

        foreach ($collection as $key => $row)
        {
            if ($key!=0) {
                ImportHarvester::create([
                    'customer_name' => $row[0],
                    'product' => $row[1],
                    'engine_no' => $row[2],
                    'chesis' => $row[3],
                ]);
            }

        }
    }
}
