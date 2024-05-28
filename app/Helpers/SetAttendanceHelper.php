<?php 
namespace App\Helpers;
use App\Attendance;
use App\Employee;
use DateTime;
use Session;


class SetAttendanceHelper{

  public function set_all_attendance_data(){
  
        $days = date('d');
        $employees = Employee::select('id','staff_id','shift_type_id','shift_id','sub_section_type_id','sub_section_id')
                                ->where('is_active',1)
                                ->get(); 

        for($i=1; $i<=$days; $i++){
            $date = date('Y-m-'.$i);
            $start = date('Y-m-'.$i.' 05:30:00');
            $next_day = date('Y-m-d',strtotime($start. '+1 days'));
            $middle_time =date('Y-m-d 03:59:59:00',strtotime($next_day));
            $end   = date('Y-m-d 11:59:59.00',strtotime($next_day));

            $datas = $this->getGivenDateData($start,$middle_time,$end);    
            
            foreach($employees as $employee){
                $filtered_datas = $this->filter_employee_data($datas,$employee->staff_id); 
                $this->setAttendanceWithInOut($date,$employee,$filtered_datas,$datas);
            }
        
        }
        return 1;
    }



    public function set_attendance_by_location_and_date($request){

        $from_date = date('Y-m-d',strtotime($request->from_date));
        $to_date   = date('Y-m-d',strtotime($request->to_date)); 
        $employees = Employee::select('id','staff_id','shift_type_id','shift_id','sub_section_type_id','sub_section_id')
                            ->where('location_id',$request->location_id)
                            ->where('department_id',$request->department_id)
                            ->where('is_active',1)
                            ->get(); 
        

        while (strtotime($from_date) <= strtotime($to_date)) {
            
            $date = $from_date;
            $start = date('Y-m-d 05:30:00',strtotime($date));
            $next_day = date('Y-m-d',strtotime($date. '+1 days'));
            $middle_time =date('Y-m-d 03:59:59:00',strtotime($next_day));
            $end   = date('Y-m-d 11:59:59.00',strtotime($next_day));

            $datas = $this->getGivenDateData($start,$middle_time,$end);    
            
            foreach($employees as $employee){
                $filtered_datas = $this->filter_employee_data($datas,$employee->staff_id); 
                $this->setAttendanceWithInOut($date,$employee,$filtered_datas,$datas);
            }

            $from_date = date ("Y-m-d", strtotime("+1 day", strtotime($from_date)));

        }

        Session::flash("success", "Set Attendance Succcessfully !");
        return redirect("/attendance_process?location_id=$request->location_id");
    }



    private function setAttendanceWithInOut($date,$employee,$filtered_datas,$all_data){
        $first_in_time = null;
        $first_out_time = null;
        $second_in_time = null;
        $second_out_time = null;
    
        $data_count = 0;
        $in_count = 0;
        $out_count = 0;

        foreach($filtered_datas as $filter_data){

            // for not In/Out employee -> one machine 
            if($employee->shift_type_id == 1 || $filter_data->InOut != 'In' || $filter_data->InOut != 'Out' ){    
                if($first_in_time == null && $data_count == 0){
                    $first_in_time = $filter_data->AttenTime;
                }else{
                    $first_out_time = $filter_data->AttenTime;
                }
            }else{ // in  out type -> multiple machine

                    if($filter_data->InOut == 'In'){ 
                        // first IN 
                        if($first_in_time == null && $in_count == 0 && $data_count == 0){
                            $first_in_time = $filter_data->AttenTime;
                        }
        
                        // second IN 
                        if($first_out_time != null && $second_in_time == null && $data_count > 0){
                            $second_in_time = $filter_data->AttenTime;
                        } 
                        $in_count++;
                    }// in data end
    
    
                    if($filter_data->InOut == 'Out'){
                            
                            // first out
                            if($second_in_time == null){
                                $first_out_time = $filter_data->AttenTime;
                            }else{
                                // second out
                                $second_out_time = $filter_data->AttenTime;
                            }
            
                            $out_count++;
                    }// out data end


             } // end else


            $data_count++;

        }

       // if any data found
       if($first_in_time != null || $first_out_time != null || $second_in_time == null || $second_out_time == null)
            $this->updateAttendanceData($date,$employee,$first_in_time,$first_out_time,$second_in_time,$second_out_time);

     return 1;

    }




    private function updateAttendanceData($date,$employee,$first_in_time,$first_out_time,$second_in_time,$second_out_time){
        $attendance = Attendance::where('employee_id',$employee->id)
                                ->where('date',$date)
                                ->where('is_process_locked',0)
                                ->where('is_final_locked',0)
                                ->where('editor_id',null)
                                ->first();                      
        if($attendance){
            $attendance->date = $date;
            $attendance->employee_id  = $employee->id;
            $attendance->in_1_time    = $first_in_time;
            $attendance->out_1_time   = $first_out_time;
            $attendance->in_2_time    = $second_in_time;
            $attendance->out_2_time   = $second_out_time;
            if($employee->sub_section_type_id == 1) $attendance->sub_section_1_id = $employee->sub_section_id;
            if($employee->shift_type_id == 1) $attendance->shift_1_id = $employee->shift_id;
            $attendance->save();
        
        }
        return 1;
    }





    private function filter_employee_data($datas,$staff_id){
        return $filtered = array_filter($datas, function($obj) use($staff_id){ 
            if($obj->EmpCode === $staff_id){
               return true;
            }else {
                return false;
            }
        });
    }


    private function getGivenDateData($start,$middle_time,$end){

        return \DB::select("SELECT staff_id as EmpCode, card_number as CardNumber, in_out as  'InOut', atten_time as AttenTime 
                  FROM raw_attendances
                  WHERE (
                                ( 
                                    atten_time BETWEEN '$start' AND '$middle_time' 
                                )
                                OR
                                (   atten_time BETWEEN '$middle_time' AND '$end' 
                                    AND in_out = 'Out' 
                                )
                            )

                  ORDER BY atten_time ASC 
                    
                ");
    }








    private function getOutData(){
        $start = date('Y-m-d');
        $end = date('Y-m-d 11:59:59.000',strtotime('+1 days'));
 
         /* 
         DeviceId: "3",
         EmpCode: "05257",
         CardNumber: "1",
         InOut: "Out",
         AttenTime: "2019-02-24 20:26:31.000",
         */
 
         return \DB::connection('sqlsrvbbl')
         ->select("WITH TOPROW AS (
                     SELECT r.DeviceId,r.EmpCode,r.CardNumber,r.InOut,r.AttenTime, ROW_NUMBER() 
                         over (
                             PARTITION BY [r].[EmpCode] 
                             order by [r].[AttenTime] DESC
                         ) AS RowNo 
                         FROM [RawData] r
                         WHERE AttenTime BETWEEN '$start' AND '$end'
                         AND r.InOut = 'Out'
                         AND r.EmpCode != ''
                     )
                     SELECT * FROM TOPROW WHERE RowNo <= 1
                 ");
     }



     public function create_attendance(){

        $employees = Employee::select('id','staff_id')
                                ->where('is_active',1)
                                ->get();

        $days = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
        
        for($i=1; $i<=$days; $i++){

            foreach($employees as $employee){
                $attendance = Attendance::where('employee_id',$employee->id)
                                ->whereDate('date',date('Y-m-'.$i))
                                ->first();
                if(!$attendance){
                    $attendance = new Attendance;
                    $attendance->date = date('Y-m-'.$i);
                    $attendance->employee_id = $employee->id;
                    $attendance->save(); 
                }                 
                
            }
        }
        dd('Done Creating Blank Date');
    }


  

     private function getTimeDifferenceInHour($start,$end){
        $start_date = new DateTime($start);
        $since_start = $start_date->diff(new DateTime($end));
        //return $since_start->h;
        $minutes = $since_start->days * 24 * 60;
        $minutes += $since_start->h * 60;
        $minutes += $since_start->i;
        return $minutes/60;
     }
     
     
}