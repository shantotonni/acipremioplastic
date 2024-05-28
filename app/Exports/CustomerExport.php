<?php

namespace App\Exports;

use App\Customer;
use App\Models\PartsDetail;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

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

        $result = [];
         $customers = Customer::query()->with('model','area');
        if ($mobile){
            $customers = $customers->where('mobile', 'like', '%' . $mobile . '%');
        }
        
        if ($from_date && $to_date){
            $customers = $customers->whereDate('created_at',">=",$from_date)
                            ->whereDate('created_at',"<=",$to_date)
                            ->orderBy('id','desc')->get();
        }else{
            $customers = $customers->orderBy('id','desc')->get();
        }
        // dd($customers);
        foreach ($customers as $customer) {
            $result[] = [
                'Id' => $customer->id,
                'Name' => $customer->name,
                'Code' => $customer->code,
                'Mobile' => $customer->mobile,
                'Chassis' => $customer->chassis,
                'Model' => isset($customer->model->name) ? $customer->model->name : '',
                'ServiceHour' => $customer->service_hour,
                'Area' => isset($customer->area) ? $customer->area->name: '',
                'CreatedAt' => $customer->created_at,
                'Address' => $customer->address
            ];
        }
        // dd($result);
        $customers = collect($result);
        return $customers;
    }

    public function headings(): array
    {
        return [
            'Id',
            'Name',
            'Code',
            'Mobile',
            'Chassis',
            'Model',
            'Service Hour',
            'Area',
            'CreatedAt',
            'Address',
        ];
    }

}
