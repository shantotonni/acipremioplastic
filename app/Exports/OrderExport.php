<?php

namespace App\Exports;

use App\Customer;
use App\Models\PartsDetail;
use App\Order;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

    protected $f_date;
    protected $t_date;
    protected $status;

    function __construct($request) {
        $this->f_date = $request->fromDate;
        $this->t_date = $request->toDate;
        $this->status = $request->order_status;
    }
 
    public function collection()
    {
        $from_date = date('Y-m-d',strtotime($this->f_date));
        $to_date = date('Y-m-d',strtotime($this->t_date));
        $status= $this->status;
//        dd($to_date);
        $result = [];

         $orders = Order::query()->with('orderProduct','customer');
//dd(Order::query());
        if ($status){
            $orders = $orders->where('order_status', 'like', '%' . $status . '%');
        }
        
        if ($from_date && $to_date){
            $orders = $orders->whereDate('created_at',">=",$from_date)
                            ->whereDate('created_at',"<=",$to_date)
                            ->orderBy('id','desc')->get();
        }else{
            $orders = $orders->orderBy('id','desc')->get();
        }

        foreach ($orders as $order) {

            $result[] = [
                'Id' => $order->id,
                'Code' => $order->code,
                'Name' => $order->name,
                'Customer Id' => $order->customer_id,
                'Mobile' => $order->mobile,
                'CustomerAddress' => $order->customer_address,
                'DeliveryAddress' => $order->delivery_address,
                'AreaName' => $order->area_name,
                'DistrictName' => $order->district_name,
                'UpazilaName' => $order->upazila_name,
                'TotalAmount' => $order->total_amount,
                'Discount' => $order->discount,
                'DeliveryCharge' => $order->delivery_charge,
                'GrandTotal' => $order->grand_total,
                'OrderStatus' => $order->order_status,
                'CreatedAt' => $order->created_at,
                'Address' => $order->address
            ];
        }
        // dd($result);
        $orders = collect($result);

        return $orders;
    }

    public function headings(): array
    {
        return [
            'Id',
            'Code',
            'Mobile',
            'Name',
            'Customer ID',
            'Customer Address',
            'Delivery Address',
            'Area Name',
            'District Name',
            'Upazila Name',
            'Total Amount',
            'Discount',
            'Delivery Charge',
            'Grand Total',
            'Order Status',
            'CreatedAt',
        ];
    }

}
