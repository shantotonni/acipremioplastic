<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Location;
use App\RawAttendance;
use App\LastSync;
use Auth;
use App\Employee;
use App\Attendance;
use Log;


class PullRaw extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:raw {monthly?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'PUll Raw Data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        $this->pull_all_raw($this->argument('monthly'));
        //$this->set_all_attendance_data();
    }


    private function pull_all_raw($arg){

        if($arg == 'monthly'){
            echo "monthly".PHP_EOL;
           $from_date = date('Y-m-1');  
        }else{
            $from_date = date('Y-m-d');
        }
           
        echo "pull_all_raw_data".PHP_EOL;
        Log::info('pull_all_raw_data '.date('d-m-Y H:i:s'));
    }







}
