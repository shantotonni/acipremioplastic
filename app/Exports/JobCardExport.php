<?php

namespace App\Exports;

use App\JobCard;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DateTime;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class JobCardExport implements FromCollection, WithHeadings, ShouldAutoSize,WithEvents
{
    protected $f_date;
    protected $t_date;
    protected $chassis_number;
    protected $approve_status;
    protected $product_id;

    function __construct($request) {
        $this->f_date = $request->f_date;
        $this->t_date = $request->t_date;
        $this->chassis_number = $request->chassis_number;
        $this->approve_status = $request->approve_status;
        $this->product_id = $request->product_id;
    }

    public function collection()
    {
        $is_approved = $this->approve_status;
        $product_id = $this->product_id;
        $chassis_number = $this->chassis_number;
        $from_date = date('Y-m-d',strtotime($this->f_date));
        $to_date = date('Y-m-d',strtotime($this->t_date));

        $job_cards = JobCard::orderBy('id','Desc')
            ->with(['area','territory','engineer','technitian','participant','call_type','service_type','product','approver','model','district','upazila','section'])
            ->whereBetween('service_date',[$from_date,$to_date])
            ->where('is_approved',$is_approved);
            if (Auth::user()->role_id != 1){
                $user_id = Auth::user()->id;
                $job_cards = $job_cards->where('engineer_id',$user_id);
            }
            if ($product_id){
                $job_cards = $job_cards->where('product_id',$product_id);
            }
            if ($chassis_number){
                $job_cards = $job_cards->where('chassis_number',$chassis_number);
            }
         $job_cards = $job_cards->get();

        $result = [];

        foreach ($job_cards as $job_card){

            if($job_card->service_wanted_at && $job_card->service_start_at){
                $datetime1 = new DateTime($job_card->service_wanted_at);
                $datetime2 = new DateTime($job_card->service_start_at);
                $interval_date = $datetime1->diff($datetime2);
                $interval = $interval_date->format('%h')." Hours ".$interval_date->format('%i')." Minutes";
            }else {
                $interval = 0;
            }

            $result[] = [
                'id' =>$job_card->id,
                'section' =>isset($job_card->section) ? $job_card->section->name : '',
                'territory' =>isset($job_card->territory) ? $job_card->territory->name : '',
                'district'=>isset($job_card->district) ? $job_card->district->name : '',
                'upazila'=>isset($job_card->upazila) ? $job_card->upazila->name:'',
                'area' =>isset($job_card->area) ? $job_card->area->name : '',
                'engineer' =>isset($job_card->engineer) ? $job_card->engineer->name : '',
                'technician' =>isset($job_card->technitian) ? $job_card->technitian->name : '',
                'technitian_username' =>isset($job_card->technitian) ? $job_card->technitian->username : '',
                'creator_name' =>$job_card->job_creator,
                'product' =>isset($job_card->product) ? $job_card->product->name : '',
                'model_name' =>isset($job_card->model) ? $job_card->model->model_name:'',
                'call_type' =>isset($job_card->call_type) ? $job_card->call_type->name : '',
                'service_type' =>isset($job_card->service_type) ? $job_card->service_type->name : '',
                'customer_name' =>$job_card->customer_name,
                'customer_moblie' =>$job_card->customer_moblie,
                'buy_date' => date('Y-m-d',strtotime($job_card->buy_date)),
                'visited_date' => date('Y-m-d',strtotime($job_card->visited_date)),
                'installation_date' => date('Y-m-d',strtotime($job_card->installation_date)),
                'service_wanted_at' => date('Y-m-d H:i:s',strtotime($job_card->service_wanted_at)),
                'service_start_at' => date('Y-m-d H:i:s',strtotime($job_card->service_start_at)),
                'service_end_at' => date('Y-m-d H:i:s',strtotime($job_card->service_end_at)),
                'hour' =>$job_card->hour,
                // 'service_income' =>$job_card->service_income,
                'is_approved' =>$job_card->is_approved,
                'rating' => $job_card->rating,
                'job_status' => $job_card->job_status,
                'chassis_number' => $job_card->chassis_number,
                'running_houre' => $job_card->running_houre,
                'spare_parts_sale' => $job_card->spare_parts_sale,
                'created_at' => date('Y-m-d H:i:s',strtotime($job_card->created_at)),
                'time_app' => $job_card->time_app,
                'service_date'=>  date('Y-m-d',strtotime($job_card->service_date)),
                'is_six'=> $interval,
                'total_service_cost'=> $job_card->total_service_cost,
                'discount_amount'=> $job_card->discount_amount,
                'total_receviable'=> $job_card->total_receviable,
                'service_income'=> $job_card->service_income,
                'participant'=> isset($job_card->participant) ? $job_card->participant->name: '',
                'approve_remarks'=> $job_card->approve_remarks
            ];
        }

        return collect($result);
    }

    public function headings(): array
    {
        return [
            'id',
            'section',
            'territory',
            'district',
            'upazila',
            'area',
            'engineer',
            'technician',
            'technitian_username',
            'creator_name',
            'product',
            'model_name',
            'call_type',
            'service_type',
            'customer_name',
            'customer_moblie',
            'buy_date',
            'visited_date' ,
            'installation_date',
            'service_wanted_at',
            'service_start_at',
            'service_end_at',
            'hour',
            // 'service_income',
            'is_approved',
            'rating',
            'job_status',
            'chassis_number',
            'running_houre',
            'spare_parts_sale',
            'created_at',
            'time_app',
            'service_date',
            'is_six',
            'total_service_cost',
            'discount_amount',
            'total_receviable',
            'service_income',
            'participant',
            'approve_remarks',
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
