<?php

namespace App\Exports;

use App\SSRExpense;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SsrExpenseExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;
 
    public function collection()
    {
        $result = [];
       $ssrExpenses = SSRExpense::with('user')->get();
        // dd($ssrExpenses);
        foreach ($ssrExpenses as $expense) {
            $result[] = [
                'Id' => $expense->id,
                'user_id' => $expense->user_id,
                'SSRName' => $expense->user->name,
                'opening_km' => $expense->opening_km,
                'closing_km' => $expense->closing_km,
                'bike_no' => $expense->bike_no,
                'period' => $expense->period,
                'opening_image' => $expense->opening_image
                
            ];
        }
        // dd($result);
        $ssrExpenses = collect($result);
        return $ssrExpenses;
    }
 
    public function headings(): array
    {
        return [
            'Id',
            'User Id',
            'SSR Name',
            'Opening KM',
            'Closing KM',
            'Bike No',
            'Period',
            'Image'
            
        ];
    }

}
