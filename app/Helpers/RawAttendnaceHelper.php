<?php
namespace App\Helpers;

use Session;
use Auth;
use App\RawAttendance;
use App\Location;
use App\LastSync;
use App\Employee;
use DB;

class RawAttendnaceHelper{

    
    
    public function pull_all_raw_data(){

        $from_date = date('Y-m-1');
        $to_date = date('Y-m-d');
        $locations = Location::all();
            
        foreach($locations as $location){
            $last_sync_time = null;

            if($location->connection != null && $location->connection != ''){
                
                $raw_attendance_arr = RawAttendance::where('location_id',$location->id)
                                                    ->whereDate('atten_time', '>=', $from_date)
                                                    ->whereDate('atten_time', '<=', $to_date)
                                                    ->pluck('sl')
                                                    ->toArray();
                                                    
                $datas =  $this->pull_raw_attendance_query($location->connection,$from_date,$to_date);                                  
                foreach($datas as $data){ 
                    // if not exist then create
                    if(!in_array($data->SerialNo, $raw_attendance_arr)){
                        $this->saveRawAttendance($data,$location->id); 
                        $last_sync_time = $data->AttenTime;
                    }
                                
                } // end foreach
                
                $this->saveLastSync($last_sync_time,$location->id,'attendance');
                
            }
            
        }

        return 1;
    }





    public function pull_location_wise_data_of_given_date($from_date,$to_date,$location_id){

        $from_date = date('Y-m-d',strtotime($from_date));
        $to_date = date('Y-m-d',strtotime($to_date));
        $location = Location::find($location_id);

        $raw_attendance_arr = RawAttendance::where('location_id',$location->id)
                                            ->whereDate('atten_time', '>=', $from_date)
                                            ->whereDate('atten_time', '<=', $to_date)
                                            ->pluck('sl')
                                            ->toArray();


        try {
            //DB::connection()->getPdo();
            $data =  DB::connection($location->connection)
                    ->table('RawData')
                    ->select('SerialNo','DeviceId','EmpCode','CardNumber','InOut','AttenTime')
                    ->where('AttenTime','>=',$from_date)
                    ->where('AttenTime',"<=",$to_date)
                    ->orderBy('AttenTime',"ASC")
                    ->chunk(250, function ($datas) use($raw_attendance_arr,$location) { 
                                $att=[];
                                foreach($datas as $data){
                                    // if not exist then create
                                    if(!in_array($data->SerialNo, $raw_attendance_arr)){
                                        $att[] = ['location_id'=>$location->id,'sl'=>$data->SerialNo,'device_id'=>$data->DeviceId,'staff_id'=>$data->EmpCode,'card_number'=>$data->CardNumber,'in_out'=>$data->InOut,'atten_time'=>$data->AttenTime];
                                        $last_sync_time = $data->AttenTime;
                                    }
                                }
                                DB::table('raw_attendances')->insert($att);
                                $att=[];
                            });    
        } catch (\Exception $e) {
            Session::flash("danger", "Unable To connect !".$e); 
        }

        // previous process 
        //$datas =  $this->pull_raw_attendance_query($location->connection,$from_date,$to_date);
        //foreach($datas as $data){            
            // if not exist then create
            // if(!in_array($data->SerialNo, $raw_attendance_arr)){
            //     $this->saveRawAttendance($data,$location->id); 
            //     $last_sync_time = $data->AttenTime;
            // }
        //}

        return 1;
    }


    private function saveRawAttendance($data,$location_id){
        $raw_attendance = new RawAttendance;
        $raw_attendance->location_id=$location_id;
        $raw_attendance->sl=$data->SerialNo;
        $raw_attendance->device_id=$data->DeviceId;
        $raw_attendance->staff_id=$data->EmpCode;
        $raw_attendance->card_number=$data->CardNumber;
        $raw_attendance->in_out=$data->InOut;
        $raw_attendance->atten_time=date("Y-m-d H:i:s",strtotime($data->AttenTime));
        $raw_attendance->save();
        return 1;
    }
    
    
    private function saveLastSync($last_sync_time,$location_id,$type){
        if($last_sync_time != null){
            $last_sync = new LastSync;
            $last_sync->location_id=$location_id;
            $last_sync->type=$type;
            $last_sync->sync_time=$last_sync_time;
            if(Auth::check()){
                $last_sync->creator_id = Auth::user()->id;   
            }else{
                $last_sync->creator_id = 1;
            }
            $last_sync ->save();
        }
        return 1;
    }


    public function pull_raw_attendance_query($connection,$from_date,$to_date){
        $start = date('Y-m-d 00:00:00',strtotime($from_date));
        $end   = date('Y-m-d 23:59:59',strtotime($to_date));
    
        $data = [];

        try {
            //DB::connection()->getPdo();
            $data =  \DB::connection($connection)
                    ->select("SELECT r.SerialNo,r.DeviceId,r.EmpCode,r.CardNumber,r.InOut,r.AttenTime
                            FROM [RawData] r
                            WHERE r.AttenTime BETWEEN '$start' AND '$end' 
                            ORDER BY r.AttenTime ASC 
                            ");         
        } catch (\Exception $e) {
            Session::flash("danger", "Unable To connect !"); 
        }

        return $data;
    }

}