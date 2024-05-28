<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Employee;
use App\Attendance;
use Log;

class MonthlyCommand extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:createall {month?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all attendance for this month';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        echo "command called".PHP_EOL;
        $this->create_attendance($this->argument('month'));
    }

    private function create_attendance($month){
        echo 'month=',$month;
        $employees = Employee::where('is_active',1)->get();
        if($month && $month>=1 && $month <=12){
            $m = $month;
        }else{
            $m = date('m');
        }

        $days = cal_days_in_month(CAL_GREGORIAN,$m,date('Y'));
        
        for($i=1; $i<=$days; $i++){

            foreach($employees as $employee){
                $attendance = Attendance::where('employee_id',$employee->id)
                                ->whereDate('date',date('Y-'.$m.'-'.$i))
                                ->first();
                if(!$attendance){
                    $attendance = new Attendance;
                    $attendance->date = date('Y-'.$m.'-'.$i);
                    $attendance->employee_id = $employee->id;
                    $attendance->save(); 
                } 
                echo '.';                
                
            }
        }
        echo "Montyhly Attendance creation done".date('d-'.$m.'-Y H:i:s').PHP_EOL;
        Log::info('Monthly Attendance creation done '.date('d-'.$m.'-Y H:i:s'));
    }

}
