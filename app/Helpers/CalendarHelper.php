<?php 
namespace App\Helpers;

use App\Calender;
use App\CalenderDetail;
use App\Holiday;
use App\Location;
use App\Department;
use App\Shift;

class CalendarHelper{

    public function createCalender(){

        $locations = Location::all();
        $departments = Department::all();
        

        foreach($locations as $location){
            $shifts = Shift::where('location_id',$location->id)->get();
            foreach($departments as $department){
                $start_date = date('Y-01-01');
                $end_date   =  date('Y-12-31'); 

                while (strtotime($start_date) <= strtotime($end_date)) {

                    $date = date("Y-m-d",strtotime($start_date));
                    $day_of_week = date('D', strtotime($start_date));
                    
                    $calender = $this->isCalenderAlreadyExist($location->id,$department->id,$date);
                    if(!$calender){   
                        $calender = new Calender;
                        $calender->date = $date;
                        $calender->location_id=$location->id;
                        $calender->department_id=$department->id;
                        $calender = $this->getLeaveStatus($calender,$day_of_week,$location->id);
                        $calender ->save();
                    }

                    
                    $this->saveCalenderDetail($calender->id,$shifts);
                    $start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
                    echo '.';
                }

            }

        }
   
    }

    private function getLeaveStatus($calender,$day_of_week,$location_id){
         
          
         if($day_of_week == 'Sat' && $location_id == 1){ //HO  location =1 
            $calender->is_holiday = 1;
            $calender->remark   ='WH';
         }

         if($day_of_week == 'Fri'){
            $calender->is_holiday = 1;
            $calender->remark   ='WH';
         }
         
         $holiday = Holiday::whereDate('date',$calender->date)->first();
         if($holiday){
            $calender->is_holiday = 1;
            $calender->remark = $calender->remark ." ".$holiday->description;
         }

        return $calender;
    }

    private function isCalenderAlreadyExist($location_id,$department_id,$date){

            $calender = Calender::where('location_id',$location_id)
                                ->where('department_id',$department_id)
                                ->whereDate('date',$date)
                                ->first();
 
            return ($calender ? $calender : 0 );                     

    }

    private function saveCalenderDetail($calender_id,$shifts){
        
        foreach($shifts as $shift){
            if(!$this->isCalenderDetailExist($calender_id,$shift->id)){
                $calender_detail = new CalenderDetail;
                $calender_detail->calender_id=$calender_id;
                $calender_detail->shift_id=$shift->id;
                $calender_detail->from_time=$shift->from_time;
                $calender_detail->to_time=$shift->to_time;
                $calender_detail->absent_time=$shift->absent_time;
                $calender_detail->half_time=$shift->half_time;
                $calender_detail->total_shift_hr=$shift->total_shift_hr;
                $calender_detail ->save();
                echo "details created";
            }
            
        }
    
        return 1;
        
    }

    private function isCalenderDetailExist($calender_id,$shift_id){
          $calender_detail = CalenderDetail::where('calender_id',$calender_id)
                                             ->where('shift_id',$shift_id)
                                             ->first();
           return ($calender_detail ? $calender_detail : 0 );                                      

    }

}