<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use Session;
use Auth;
use App\Attendance;
use App\Employee;
use App\Department;
use App\Status;
use App\Shift;
use App\Location;
use App\UserLocation;
use App\UserDepartment;
use App\Section;
use App\EmployeeType;
use App\Calender;
use App\ShiftType;
use DB;
use Log;



class AttendanceProcessHelper{
    
    public function allAttendanceProcess($location_id,$department_id,$from_date,$to_date){
        return DB::update(" UPDATE att 
                            SET att.shift_1_id = aedit.shift_1_id,
                                att.shift_1_hour = aedit.shift_1_hour,
                                att.shift_1_worked_hour = aedit.shift_1_worked_hour,
                                att.short_excess_1 = CASE WHEN aedit.shift_1_hour > 0 THEN (aedit.shift_1_worked_hour - aedit.shift_1_hour) ELSE 0 END,
                                att.shift_1_ot_hour = aedit.shift_1_ot_hour,
                                att.shift_1_start = COALESCE(aedit.shift_1_start,null),
                                att.shift_1_end = COALESCE(aedit.shift_1_end,null),
                                
                                att.shift_2_id = aedit.shift_2_id,
                                att.shift_2_hour = aedit.shift_2_hour,
                                att.shift_2_worked_hour = aedit.shift_2_worked_hour,
                                att.short_excess_2 = CASE WHEN aedit.shift_2_hour > 0 THEN (aedit.shift_2_worked_hour - aedit.shift_2_hour) ELSE 0 END,
                                att.shift_2_ot_hour = aedit.shift_2_ot_hour,
                                att.shift_2_start = COALESCE(aedit.shift_2_start,null),
                                att.shift_2_end = COALESCE(aedit.shift_2_end,null),
                                
                                att.total_working_hour = aedit.shift_1_hour + aedit.shift_2_hour,
                                att.total_worked_hour  = aedit.shift_1_worked_hour + aedit.shift_2_worked_hour,
                                -- more than 8 hour is ot hour
                                att.total_ot_hour      =CASE WHEN aedit.is_ot_enabled  = 1 AND aedit.is_off_day = 0  THEN (aedit.shift_1_ot_hour + aedit.shift_2_worked_hour) ELSE 0 END,
                                att.total_short_excess =CASE WHEN aedit.shift_1_hour > 0 THEN (aedit.shift_1_worked_hour - aedit.shift_1_hour) ELSE 0 END  + 
                                                        CASE WHEN aedit.shift_2_hour > 0 THEN (aedit.shift_2_worked_hour - aedit.shift_2_hour) ELSE 0 END,
                                att.shift_1_payable_hour = CASE WHEN aedit.shift_1_worked_hour < aedit.shift_1_hour THEN aedit.shift_1_worked_hour ELSE aedit.shift_1_worked_hour - aedit.shift_1_ot_hour  END,
                                att.is_holiday = aedit.is_holiday,
                                att.status_id = CASE
                                                    WHEN aedit.is_off_day = 1 THEN 5 -- off day
                                                    WHEN aedit.is_leave   = 1 THEN 6 --leave
                                                    WHEN aedit.is_holiday = 1 THEN 4 --holiday 
                                                    WHEN aedit.is_leave = 0 AND aedit.is_off_day = 0 AND aedit.is_holiday = 0 AND (aedit.in_1_time > aedit.shift_1_start OR aedit.in_2_time > aedit.shift_2_start) THEN 3 -- late
                                                    WHEN aedit.is_leave = 0 AND aedit.is_off_day = 0 AND aedit.is_holiday = 0 AND aedit.shift_1_worked_hour < 1 THEN 1 --absent
                                                    WHEN aedit.is_leave = 0 AND aedit.is_off_day = 0 AND aedit.is_holiday = 0 AND (aedit.shift_1_worked_hour >= aedit.shift_1_hour OR aedit.shift_2_worked_hour >= aedit.shift_2_hour) THEN 2 --present
                                                    ELSE 1
                                                END 
                             from attendances att 
                             JOIN(	
                                     SELECT a.id as id,
                                     a.[date] as date, 
                                     c.is_holiday as is_holiday, -- get holiday from calender
                                     a.is_leave as is_leave, 
                                     a.is_off_day as is_off_day, 
                                     a.in_1_time as in_1_time,
                                     a.in_2_time as in_2_time,
                                     CASE WHEN cd1.shift_id IS NULL THEN NULL ELSE cd1.shift_id END  as shift_1_id,
                                     CASE WHEN cd2.shift_id IS NULL THEN NULL ELSE cd2.shift_id END  as shift_2_id,
                                     
                                     CASE WHEN cd1.from_time IS NULL THEN NULL ELSE CONVERT(DATETIME, CONVERT(varchar(20), a.date,101)  + ' ' + CONVERT(varchar(8), cd1.from_time, 108)) END  as shift_1_start,
                                     CASE WHEN cd2.from_time IS NULL THEN NULL ELSE CONVERT(DATETIME, CONVERT(varchar(20), a.date,101)  + ' ' + CONVERT(varchar(8), cd2.from_time, 108)) END  as shift_2_start,
                                 
                                     CASE WHEN cd1.to_time IS NULL THEN NULL ELSE CONVERT(DATETIME, CONVERT(varchar(20), a.date,101)  + ' ' + CONVERT(varchar(8), cd1.to_time, 108)) END  as shift_1_end,
                                     CASE WHEN cd2.to_time IS NULL THEN NULL ELSE CONVERT(DATETIME, CONVERT(varchar(20), a.date,101)  + ' ' + CONVERT(varchar(8), cd2.to_time, 108)) END  as shift_2_end,
                                     
                                     CASE
                                         WHEN a.is_off_day = 1  THEN 0
                                         WHEN cd1.total_shift_hr IS NULL THEN 0 ELSE cd1.total_shift_hr END  as shift_1_hour,
                                     CASE
                                         WHEN a.is_off_day = 1 THEN 0 
                                         WHEN cd2.total_shift_hr IS NULL THEN 0 ELSE cd2.total_shift_hr END  as shift_2_hour,
                                     
                                     e.location_id as location_id, 
                                     e.department_id as department_id,
                                     e.is_ot_enabled, 
                                     
                                     -- calculation
                                     CASE
                                         WHEN a.is_off_day = 1 THEN 0
                                         WHEN DATEDIFF(SECOND,a.in_1_time, a.out_1_time) IS NULL THEN 0 ELSE DATEDIFF(SECOND,a.in_1_time, a.out_1_time)/3600.0 END  AS  shift_1_worked_hour,
                                     CASE
                                         WHEN a.is_off_day = 1 THEN 0
                                         WHEN DATEDIFF(SECOND,a.in_2_time, a.out_2_time) IS NULL THEN 0 ELSE DATEDIFF(SECOND,a.in_2_time, a.out_2_time)/3600.0 END  AS  shift_2_worked_hour,
                             
                                     CASE WHEN e.is_ot_enabled = 1 AND a.is_off_day = 0 AND (shift_1_worked_hour - shift_1_hour) >= l.min_ot_hr  AND shift_1_hour > 0  AND shift_1_worked_hour > shift_1_hour  THEN (shift_1_worked_hour - shift_1_hour) ELSE 0 END AS  shift_1_ot_hour,
                                     CASE WHEN e.is_ot_enabled = 1 AND a.is_off_day = 0 AND shift_2_worked_hour >= l.min_ot_hr  AND shift_2_worked_hour > shift_2_hour THEN (shift_2_worked_hour - shift_2_hour) ELSE 0 END AS  shift_2_ot_hour
                                 
                                     from [attendances] as  a
                                     
                                     inner join [employees] as e on [e].[id] = [a].[employee_id]
                                     inner join [locations] as l on l.id = e.[location_id]
                                     left join [calenders] as c
                                         on  a.[date] = c.[date]
                                         and [e].[location_id] = [c].[location_id] 
                                         and [e].[department_id] = [c].[department_id]
                                     left join [calender_details] as cd1 on [cd1].[calender_id] = [c].[id] 
                                         --and [cd1].[shift_id]  = CASE WHEN e.[shift_type_id] = 1 THEN [e].[shift_id] ELSE a.[shift_1_id] END
                                         and [cd1].[shift_id] = a.[shift_1_id]          
                                     left join [calender_details] as cd2 on [cd2].[calender_id] = [c].[id]  
                                         and [cd2].[shift_id] = a.[shift_2_id]      
                                         
                                     where [e].[location_id] = '$location_id' 
                                     and [e].[department_id] =  '$department_id'
                                     and a.[date] >= '$from_date' 
                                     and a.[date] <= '$to_date' 
                                 ) aedit ON aedit.id = att.id
                             ");
     }
     
     
     
     

     public function checkSheduleAndCalender($location_id,$department_id,$from_date,$to_date){
        return DB::select("SELECT a.[date],e.[staff_id],e.[employee_type_id],a.[shift_1_id],a.[shift_2_id],cd1.[shift_id] as cal_shift_1_id,cd2.[shift_id] as cal_shift_2_id 
                              from [attendances] as  a
                              inner join [employees] as e on [e].[id] = [a].[employee_id]
                              left join [calenders] as c
                                  on  a.[date] = c.[date]
                                  and [e].[location_id] = [c].[location_id] 
                                  and [e].[department_id] = [c].[department_id]
                              left join [calender_details] as cd1 on [cd1].[calender_id] = [c].[id] 
                                  and [cd1].[shift_id]  =  a.[shift_1_id]         
                              left join [calender_details] as cd2 on [cd2].[calender_id] = [c].[id]  
                                  and [cd2].[shift_id] = a.[shift_2_id] 
                              WHERE e.shift_type_id = 2
                                  AND e.[location_id] = '$location_id' 
                                  AND e.[department_id] =  '$department_id'
                                  AND a.[date] >= '$from_date' 
                                  AND a.[date] <= '$to_date' 
                                  AND (
                                          ((a.[in_1_time] IS NOT NULL OR a.[out_1_time] IS NOT NULL) AND a.shift_1_id IS NULL)
                                          OR 
                                          ((a.[in_2_time] IS NOT NULL OR a.[out_2_time] IS NOT NULL) AND a.shift_2_id IS NULL)
                                          OR
                                          ((a.[in_1_time] IS NOT NULL OR a.[out_1_time] IS NOT NULL) AND a.sub_section_1_id IS NULL)
                                          OR 
                                          ((a.[in_2_time] IS NOT NULL OR a.[out_2_time] IS NOT NULL) AND a.sub_section_2_id IS NULL)
                                          OR
                                          (a.shift_1_id IS NOT NULL AND cd1.shift_id IS NULL) 
                                          OR
                                          (a.shift_2_id IS NOT NULL AND cd2.shift_id IS NULL)
                                      )
                              
                          ");
  
      }
 
 
 
 
 
 
 
 
 
 
 
 
    public function variableShiftAttendanceData($location_id,$department_id,$from_date,$to_date){
     return DB::update(" UPDATE att 
                         SET att.shift_1_id = aedit.shift_1_id,
                             att.shift_1_hour = aedit.shift_1_hour,
                             att.shift_1_worked_hour = aedit.shift_1_worked_hour,
                             att.short_excess_1 = aedit.short_excess_1,
                             att.shift_1_ot_hour = aedit.shift_1_ot_hour,
                             att.shift_1_start = COALESCE(aedit.shift_1_start,null),
                             att.shift_1_end = COALESCE(aedit.shift_1_end,null),
                             
                             att.shift_2_id = aedit.shift_2_id,
                             att.shift_2_hour = aedit.shift_2_hour,  
                             att.shift_2_worked_hour = aedit.shift_2_worked_hour,
                             att.short_excess_2 = aedit.short_excess_2,
                             att.shift_2_ot_hour = aedit.shift_2_ot_hour,
                             att.shift_2_start = COALESCE(aedit.shift_2_start,null),
                             att.shift_2_end = COALESCE(aedit.shift_2_end,null),
                             
                             att.total_working_hour = aedit.total_working_hour,
                             att.total_worked_hour = aedit.total_worked_hour,
                             att.total_ot_hour     = CASE WHEN aedit.is_ot_enabled  = 1 THEN (aedit.shift_1_ot_hour + aedit.shift_2_ot_hour) ELSE 0 END
                             
                         from attendances att 
                         JOIN(
                             SELECT a.id as id,a.[date] as date,
                             
                             CASE WHEN cd1.shift_id IS NULL THEN NULL ELSE cd1.shift_id END  as shift_1_id,
                             CASE WHEN cd2.shift_id IS NULL THEN NULL ELSE cd2.shift_id END  as shift_2_id,
                             
                             CASE WHEN cd1.from_time IS NULL THEN NULL ELSE CONVERT(DATETIME, CONVERT(varchar(20), a.date,101)  + ' ' + CONVERT(varchar(8), cd1.from_time, 108)) END  as shift_1_start,
                             CASE WHEN cd2.from_time IS NULL THEN NULL ELSE CONVERT(DATETIME, CONVERT(varchar(20), a.date,101)  + ' ' + CONVERT(varchar(8), cd2.from_time, 108)) END  as shift_2_start,
                             
                             CASE WHEN cd1.to_time IS NULL THEN NULL ELSE CONVERT(DATETIME, CONVERT(varchar(20), a.date,101)  + ' ' + CONVERT(varchar(8), cd1.to_time, 108)) END  as shift_1_end,
                             CASE WHEN cd2.to_time IS NULL THEN NULL ELSE CONVERT(DATETIME, CONVERT(varchar(20), a.date,101)  + ' ' + CONVERT(varchar(8), cd1.to_time, 108)) END  as shift_2_end,
                             
                             CASE WHEN cd1.total_shift_hr IS NULL THEN 0 ELSE cd1.total_shift_hr END  as shift_1_hour,
                             CASE WHEN cd2.total_shift_hr IS NULL THEN 0 ELSE cd2.total_shift_hr END  as shift_2_hour,
                             
                             e.location_id as location_id, e.department_id as department_id,e.is_ot_enabled, 
                             -- calculation
                             CASE WHEN DATEDIFF(SECOND,a.in_1_time, a.out_1_time) IS NULL THEN 0 ELSE DATEDIFF(SECOND,a.in_1_time, a.out_1_time)/3600.0 END  AS  shift_1_worked_hour,
                             CASE WHEN DATEDIFF(SECOND,a.in_2_time, a.out_2_time) IS NULL THEN 0 ELSE DATEDIFF(SECOND,a.in_2_time, a.out_2_time)/3600.0 END  AS  shift_2_worked_hour,
                             
                             (shift_1_worked_hour - shift_1_hour) as short_excess_1,
                             (shift_2_worked_hour - shift_2_hour) as short_excess_2,
                             ((shift_1_worked_hour - shift_1_hour) + (shift_2_worked_hour - shift_2_hour)) as total_short_excess,
                             (shift_1_worked_hour + shift_2_worked_hour) as total_worked_hour,
                             (shift_1_hour + shift_2_hour) as total_working_hour,
                             
                             CASE WHEN e.is_ot_enabled  = 1 AND shift_1_worked_hour > shift_1_hour  THEN (shift_1_worked_hour - shift_1_hour) ELSE 0 END AS  shift_1_ot_hour,
                             CASE WHEN e.is_ot_enabled  = 1 AND shift_2_worked_hour > shift_2_hour THEN (shift_2_worked_hour - shift_2_hour) ELSE 0 END AS  shift_2_ot_hour
                                                         
                             
                             from [attendances] as  a
                             
                             inner join [employees] as e on [e].[id] = [a].[employee_id]
                             left join [calenders] as c
                                 on  a.[date] = c.[date]
                                 and [e].[location_id] = [c].[location_id] 
                                 and [e].[department_id] = [c].[department_id]
                             left join [calender_details] as cd1 on [cd1].[calender_id] = [c].[id] 
                                 and [a].[shift_1_id] = [cd1].[shift_id]               
                             left join [calender_details] as cd2 on [cd2].[calender_id] = [c].[id] 
                                 and [a].[shift_2_id] = [cd2].[shift_id]      
                                                         
                             where [e].[location_id] = '$location_id' 
                             and [e].[department_id] =  '$department_id'
                             and a.[date] >= '$from_date' 
                             and a.[date] <= '$to_date' 
                             and [e].[shift_type_id] = '2' -- fixed shift type
 
                         ) aedit ON aedit.id = att.id 
                     ");
     }
 
     public function fixedShiftAttendanceData($location_id,$department_id,$from_date,$to_date){
 
     return DB::update(" UPDATE att 
                         SET att.shift_1_id = aedit.shift_1_id,
                             att.shift_1_hour = aedit.shift_1_hour,
                             att.shift_1_worked_hour = aedit.shift_1_worked_hour,
                             att.short_excess_1 = aedit.short_excess_1,
                             att.shift_1_ot_hour = aedit.shift_1_ot_hour,
                             att.shift_1_start = COALESCE(aedit.shift_1_start,null),
                             att.shift_1_end = COALESCE(aedit.shift_1_end,null),
                             
                             att.shift_2_id = aedit.shift_2_id,
                             att.shift_2_hour = aedit.shift_2_hour,  
                             att.shift_2_worked_hour = aedit.shift_2_worked_hour,
                             att.short_excess_2 = aedit.short_excess_2,
                             att.shift_2_ot_hour = aedit.shift_2_ot_hour,
                             att.shift_2_start = COALESCE(aedit.shift_2_start,null),
                             att.shift_2_end = COALESCE(aedit.shift_2_end,null),
                             
                             att.total_working_hour = aedit.total_working_hour,
                             att.total_worked_hour = aedit.total_worked_hour,
                             att.total_ot_hour     = CASE WHEN aedit.is_ot_enabled  = 1 THEN (aedit.shift_1_ot_hour + aedit.shift_2_ot_hour) ELSE 0 END
                             
                         from attendances att 
                         JOIN(
                             SELECT a.id as id,a.[date] as date,
                             
                             CASE WHEN cd1.shift_id IS NULL THEN NULL ELSE cd1.shift_id END  as shift_1_id,
                             CASE WHEN cd2.shift_id IS NULL THEN NULL ELSE cd2.shift_id END  as shift_2_id,
                             
                             CASE WHEN cd1.from_time IS NULL THEN NULL ELSE CONVERT(DATETIME, CONVERT(varchar(20), a.date,101)  + ' ' + CONVERT(varchar(8), cd1.from_time, 108)) END  as shift_1_start,
                             CASE WHEN cd2.from_time IS NULL THEN NULL ELSE CONVERT(DATETIME, CONVERT(varchar(20), a.date,101)  + ' ' + CONVERT(varchar(8), cd2.from_time, 108)) END  as shift_2_start,
                             
                             CASE WHEN cd1.to_time IS NULL THEN NULL ELSE CONVERT(DATETIME, CONVERT(varchar(20), a.date,101)  + ' ' + CONVERT(varchar(8), cd1.to_time, 108)) END  as shift_1_end,
                             CASE WHEN cd2.to_time IS NULL THEN NULL ELSE CONVERT(DATETIME, CONVERT(varchar(20), a.date,101)  + ' ' + CONVERT(varchar(8), cd1.to_time, 108)) END  as shift_2_end,
                             
                             CASE WHEN cd1.total_shift_hr IS NULL THEN 0 ELSE cd1.total_shift_hr END  as shift_1_hour,
                             CASE WHEN cd2.total_shift_hr IS NULL THEN 0 ELSE cd2.total_shift_hr END  as shift_2_hour,
                             
                             e.location_id as location_id, e.department_id as department_id,e.is_ot_enabled, 
                             -- calculation
                             CASE WHEN DATEDIFF(HOUR,a.in_1_time, a.out_1_time) IS NULL THEN 0 ELSE DATEDIFF(HOUR,a.in_1_time, a.out_1_time) END  AS  shift_1_worked_hour,
                             CASE WHEN DATEDIFF(HOUR,a.in_2_time, a.out_2_time) IS NULL THEN 0 ELSE DATEDIFF(HOUR,a.in_2_time, a.out_2_time) END  AS  shift_2_worked_hour,
                             
                             (shift_1_worked_hour - shift_1_hour) as short_excess_1,
                             (shift_2_worked_hour - shift_2_hour) as short_excess_2,
                             ((shift_1_worked_hour - shift_1_hour) + (shift_2_worked_hour - shift_2_hour)) as total_short_excess,
                             (shift_1_worked_hour + shift_2_worked_hour) as total_worked_hour,
                             (shift_1_hour + shift_2_hour) as total_working_hour,
                             
                             CASE WHEN e.is_ot_enabled  = 1 AND shift_1_worked_hour > shift_1_hour  THEN (shift_1_worked_hour - shift_1_hour) ELSE 0 END AS  shift_1_ot_hour,
                             CASE WHEN e.is_ot_enabled  = 1 AND shift_2_worked_hour > shift_2_hour THEN (shift_2_worked_hour - shift_2_hour) ELSE 0 END AS  shift_2_ot_hour
                                                         
                             from [attendances] as  a
                             
                             inner join [employees] as e on [e].[id] = [a].[employee_id]
                             left join [calenders] as c
                                 on  a.[date] = c.[date]
                                 and [e].[location_id] = [c].[location_id] 
                                 and [e].[department_id] = [c].[department_id]
                             left join [calender_details] as cd1 on [cd1].[calender_id] = [c].[id] 
                                 and [e].[shift_id] = [cd1].[shift_id]              
                             left join [calender_details] as cd2 on [cd2].[calender_id] = [c].[id] 
                                 and [e].[shift_id] = [cd2].[shift_id]      
                                                         
                             where [e].[location_id] = '$location_id' 
                             and [e].[department_id] =  '$department_id'
                             and a.[date] >= '$from_date' 
                             and a.[date] <= '$to_date' 
                             and [e].[shift_type_id] = '1' -- fixed shift type
 
                         ) aedit ON aedit.id = att.id 
                 ");
    }
 
 
 
 
 

 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
   // #manual process start
   // ##########################
 
 
 
    private function processAttendanceOfEmployee($employee,$from_date,$to_date){
         $attendances = Attendance::where('employee_id',$employee->id)
                         ->whereDate('date', '>=', $from_date)
                         ->whereDate('date', '<=', $to_date)
                         ->get();
 
         foreach($attendances as $attendance){
             //here order is important
             $attendance = $this->getEmployeeShiftHour($employee,$attendance);
             $attendance->total_working_hour = $attendance->shift_1_hour + $attendance->shift_2_hour; 
             $attendance = $this->shiftCalculation($attendance);
             if($employee->is_ot_enabled == 1) 
                $attendance = $this->otCalculation($attendance);
             $attendance = $this->getStatus($attendance);
             $attendance->save();
         }
 
    }
 
 
 
 private function getEmployeeShiftHour($employee,$attendance){
    
     // fixed shift
     if($employee->shift_type_id == 1){
          
          $calender_shift = $this->getCalendarShift($attendance->date,$employee->location_id,$employee->department_id,$employee->shift_id);
         
          if($calender_shift){
              $attendance->shift_1_id    = $calender_shift->shift_id;
              $attendance->shift_1_start = date('Y-m-d H:i:s',strtotime($attendance->date.' '.$calender_shift->from_time));
              $attendance->shift_1_end   = date('Y-m-d H:i:s',strtotime($attendance->date.' '.$calender_shift->to_time));
              $attendance->shift_1_hour =  $calender_shift->total_shift_hr;
 
          }else{
              $this->process_errors[] = array('attendance_id' => $attendance->id,'date' => $attendance->date, 'staff_id'=>$employee->staff_id,'error'=>"Calendar Undefined");
              Log::error("Calendar Undefined attendance_id=$attendance->id, att_date=$attendance->date , employee_id=$employee->id");
          }
        
           $attendance->shift_2_hour = 0;
 
     }else{
 
          
          $attendance->shift_1_hour =  8; 
 
 
          if($attendance->in_2_time != null || $attendance->out_2_time != null){
 
             // $attendance->shift_2_hour =  8;  
 
              $calender_shift = $this->getCalendarShift($attendance->date,$employee->location_id,$employee->department_id,$employee->shift_id);
              if($calender_shift){
                  $attendance->shift_2_id    = $calender_shift->shift_id;
                  $attendance->shift_2_start = date('Y-m-d H:i:s',strtotime($attendance->date.' '.$calender_shift->from_time));
                  $attendance->shift_2_end   = date('Y-m-d H:i:s',strtotime($attendance->date.' '.$calender_shift->to_time));
                  $attendance->shift_2_hour =  $calender_shift->total_shift_hr;
  
              }else{
                  $this->process_errors[] = array('attendance_id' => $attendance->id, 'staff_id'=>$employee->staff_id,'error'=>"Calendar Undefined");
                  Log::error("Calendar Undefined attendance_id=$attendance->id, att_date=$attendance->date , employee_id=$employee->id");
              }
 
          }else{
 
              $attendance->shift_2_hour =  0;
          }
       
     
     }
 
     return $attendance;
 
 }
 
 
 private function getCalendarShift($date,$location_id,$department_id,$shift_id){
     return Calender::join('calender_details','calender_details.calender_id','calenders.id')
                      ->where('calenders.date',$date)
                      ->where('calenders.location_id',$location_id)
                      ->where('calenders.department_id',$department_id)
                      ->where('calender_details.shift_id',$shift_id)
                      ->first();
 }
 
 
 private function filterCalenderData($datas,$_date,$location_id,$department_id,$shift_id){
     return  array_filter($datas, function($obj) use($_date,$location_id,$department_id,$shift_id){ 
         if($obj->date == $_date &&$obj->location_id == $location_id && $obj->department_id== $department_id && $obj->shift_id == $shift_id){
            return true;
         }else {
             return false;
         }
     });
 }
 
 private function shiftCalculation($attendance){
      $shift_1_worked_hour = 0;
      $shift_2_worked_hour = 0;
 
      if($attendance->in_1_time != null && $attendance->out_1_time != null ){
         $attendance->shift_1_worked_hour  = $this->getTimeDifferenceInHour($attendance->out_1_time,$attendance->in_1_time);          
      }
 
      if($attendance->in_2_time != null && $attendance->out_2_time != null ){
          $attendance->shift_2_worked_hour = $this->getTimeDifferenceInHour($attendance->out_2_time,$attendance->in_2_time);
      }
 
      $attendance->total_worked_hour  =  $attendance->shift_1_worked_hour  + $attendance->shift_2_worked_hour; 
      
      $attendance->short_excess_1     = $attendance->shift_1_worked_hour - $attendance->shift_1_hour;
      $attendance->short_excess_2     = $attendance->shift_2_worked_hour - $attendance->shift_2_hour;
      $attendance->total_short_excess = $attendance->short_excess_1 + $attendance->short_excess_2;
            
     return $attendance;
 }
 
 
     private function otCalculation($attendance){
         // ot calculation;
         if($attendance->short_excess_1 >= 1){
             $attendance->shift_1_ot_hour = floor($attendance->short_excess_1);
         }
 
         if($attendance->short_excess_2 >= 1){
             $attendance->shift_2_ot_hour = floor($attendance->short_excess_2);
         }
         
         $attendance->total_ot_hour     =  $attendance->shift_1_ot_hour  + $attendance->shift_2_ot_hour;
         return $attendance;
     }
 
 
     private function getStatus($attendance){
         if($attendance->total_worked_hour > 0){
             $attendance->status_id = 2; // Present 
         }else{
             $attendance->status_id = 1; // Absend  
         }
         return $attendance;
     }
 
 
     private function getTimeDifferenceInHour($time1,$time2){
         $t1 = strtotime( $time1 );
         $t2 = strtotime( $time2 );
         $diff = $t1 - $t2;
         return $hours = $diff / 3600;
     }
     
     
     
}