<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TractorDemonstrationCollection;
use App\TractorCheckList;
use App\TractorCompititorParticipantInfo;
use App\TractorCultivationTrailReport;
use App\TractorDemonstrationModelImage;
use App\TractorDemonstrationRecordEntry;
use App\TractorSalesInquiry;
use App\ParticipantImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class TractorDemonstration extends Controller
{
    public function tractorDemonstration(Request $request){

        DB::beginTransaction();
        try {
            $tractor_demonstration_record_entry     = $request->tractor_demonstration_record_entry;
            $tractor_cultivation_trials_report      = $request->tractor_cultivation_trials_report;
            $tractor_competitors_participants_info  = $request->tractor_competitors_participants_info;//array data
            $tractor_demonstration_model_image      = $request->tractor_demonstration_model_image;//array data
            $tractor_check_list                     = $request->tractor_check_list;//array data
            $tractor_sales_inquiry                  = $request->tractor_sales_inquiry;//array data
            $tractor_participant_image              = $request->tractor_participant_image;//array data

            $demonstration_record_entry                                 = new TractorDemonstrationRecordEntry();
            $demonstration_record_entry->date                           = date('Y-m-d',strtotime($tractor_demonstration_record_entry['date']));
            $demonstration_record_entry->area_id                        = $tractor_demonstration_record_entry['area_id'];
            $demonstration_record_entry->purpose_of_demo                = $tractor_demonstration_record_entry['purpose_of_demo'];
            $demonstration_record_entry->market_type                    = $tractor_demonstration_record_entry['market_type'];
            $demonstration_record_entry->territory_id                   = $tractor_demonstration_record_entry['territory_id'];
            $demonstration_record_entry->place                          = $tractor_demonstration_record_entry['place'];
            $demonstration_record_entry->total_participant_number       = $tractor_demonstration_record_entry['total_participant_number'];
            $demonstration_record_entry->competitord_participant_number = $tractor_demonstration_record_entry['competitord_participant_number'];
            $demonstration_record_entry->sales_inquiry_number           = $tractor_demonstration_record_entry['sales_inquiry_number'];
            $demonstration_record_entry->latitude                       = $tractor_demonstration_record_entry['latitude'];
            $demonstration_record_entry->longitude                      = $tractor_demonstration_record_entry['longitude'];
            $demonstration_record_entry->save();

            $tractor_cultivation                                            = new TractorCultivationTrailReport();
            $tractor_cultivation->TDREID                                    = $demonstration_record_entry->id;
            $tractor_cultivation->tractor_model                             = $tractor_cultivation_trials_report['tractor_model'];
            $tractor_cultivation->implement                                 = $tractor_cultivation_trials_report['implement'];
            $tractor_cultivation->erpm                                      = $tractor_cultivation_trials_report['erpm'];
            $tractor_cultivation->soil_type                                 = $tractor_cultivation_trials_report['soil_type'];
            $tractor_cultivation->gear_used_first_cut                       = $tractor_cultivation_trials_report['gear_used_first_cut'];
            $tractor_cultivation->gear_used_second_cut                      = $tractor_cultivation_trials_report['gear_used_second_cut'];
            $tractor_cultivation->erpm_drop_fist_cut                        = $tractor_cultivation_trials_report['erpm_drop_fist_cut'];
            $tractor_cultivation->erpm_drop_second_cut                      = $tractor_cultivation_trials_report['erpm_drop_second_cut'];
            $tractor_cultivation->time_token_fist_cut                       = $tractor_cultivation_trials_report['time_token_fist_cut'];
            $tractor_cultivation->time_token_second_cut                     = $tractor_cultivation_trials_report['time_token_second_cut'];
            $tractor_cultivation->fuel_consumption_first_cut                = $tractor_cultivation_trials_report['fuel_consumption_first_cut'];
            $tractor_cultivation->fuel_consumption_second_cut               = $tractor_cultivation_trials_report['fuel_consumption_second_cut'];
            $tractor_cultivation->area_cover_first_cut                      = $tractor_cultivation_trials_report['area_cover_first_cut'];
            $tractor_cultivation->area_cover_second_cut                     = $tractor_cultivation_trials_report['area_cover_second_cut'];
            $tractor_cultivation->depth_of_cut_first_cut                    = $tractor_cultivation_trials_report['depth_of_cut_first_cut'];
            $tractor_cultivation->depth_of_cut_second_cut                   = $tractor_cultivation_trials_report['depth_of_cut_second_cut'];
            $tractor_cultivation->calculative_fuel_consumption_first_cut    = $tractor_cultivation_trials_report['calculative_fuel_consumption_first_cut'];
            $tractor_cultivation->calculative_fuel_consumption_second_cut   = $tractor_cultivation_trials_report['calculative_fuel_consumption_second_cut'];
            $tractor_cultivation->calculative_litre_per_acre_first_cut      = $tractor_cultivation_trials_report['calculative_litre_per_acre_first_cut'];
            $tractor_cultivation->calculative_litre_per_acre_second_cut     = $tractor_cultivation_trials_report['calculative_litre_per_acre_second_cut'];
            $tractor_cultivation->calculative_acre_per_hr_first_cut         = $tractor_cultivation_trials_report['calculative_acre_per_hr_first_cut'];
            $tractor_cultivation->calculative_acre_per_hr_second_cut        = $tractor_cultivation_trials_report['calculative_acre_per_hr_second_cut'];
            $tractor_cultivation->average_fuel_consumption                  = $tractor_cultivation_trials_report['average_fuel_consumption'];
            $tractor_cultivation->average_litre_per_acre                    = $tractor_cultivation_trials_report['average_litre_per_acre'];
            $tractor_cultivation->average_acre_per_hr                       = $tractor_cultivation_trials_report['average_acre_per_hr'];
            $tractor_cultivation->average_bigha_per_hour                    = $tractor_cultivation_trials_report['average_bigha_per_hour'];
            $tractor_cultivation->average_litre_per_bigha                   = $tractor_cultivation_trials_report['average_litre_per_bigha'];
            $tractor_cultivation->average_depth_of_cut                      = $tractor_cultivation_trials_report['average_depth_of_cut'];
            $tractor_cultivation->status                                    = 1;
            $tractor_cultivation->save();

            foreach ($tractor_competitors_participants_info as $value){
                $tractor_compititor                 = new TractorCompititorParticipantInfo();
                $tractor_compititor->TDREID         = $demonstration_record_entry->id;
                $tractor_compititor->name           = $value['name'];
                $tractor_compititor->contact_number = $value['contact_number'];
                $tractor_compititor->brand_name     = $value['brand_name'];
                $tractor_compititor->save();
            }

            foreach ($tractor_check_list as $check){
                $tractor_check_list                 = new TractorCheckList();
                $tractor_check_list->TDREID         = $demonstration_record_entry->id;
                $tractor_check_list->name           = $check['name'];
                $tractor_check_list->save();
            }

            foreach ($tractor_sales_inquiry as $inquiry){
                $tractor_check_list                  = new TractorSalesInquiry();
                $tractor_check_list->TDREID          = $demonstration_record_entry->id;
                $tractor_check_list->name            = $inquiry['name'];
                $tractor_check_list->contact_number  = $inquiry['contact_number'];
                $tractor_check_list->prefer_model    = $inquiry['prefer_model'];
                $tractor_check_list->inquiry_type    = $inquiry['inquiry_type'];
                $tractor_check_list->save();
            }

            foreach ($tractor_demonstration_model_image as $item){
                $image = $item['image'];
//                $image = str_replace('data:image/png;base64,', '', $image);
//                //$image = str_replace(' ', '+', $image);
//                $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1]);
//                Image::make($image)->save(public_path('demonstration/').$name);

                $image      = str_replace('data:image/png;base64,', '', $image);
                $image      = str_replace(' ', '+', $image);
                $imageName  = Str::random(10).'_'.date('Y-m-d').'_'. '.png';
                Image::make($image)->resize(500,500,function ($constraint){
                    $constraint->aspectRatio();
                })->save(public_path('demonstration/').$imageName);

                $tractor_model_image            = new TractorDemonstrationModelImage();
                $tractor_model_image->TDREID    = $demonstration_record_entry->id;
                $tractor_model_image->image     = $imageName;
                $tractor_model_image->save();
            }  
            
            foreach ($tractor_participant_image as $participant){

                $participant_image = $participant['image'];

                $participant_image      = str_replace('data:image/png;base64,', '', $participant_image);
                $participant_image      = str_replace(' ', '+', $participant_image);
                $participantImageName  = Str::random(10).'_'.date('Y-m-d').'_'. '.jpg';
                Image::make($participant_image)->resize(500,500,function ($constraint){
                    $constraint->aspectRatio();
                })->save(public_path('demonstration/').$participantImageName);

                $tractor_model_image            = new ParticipantImage();
                $tractor_model_image->TDREID    = $demonstration_record_entry->id;
                $tractor_model_image->image     = $participantImageName;
                $tractor_model_image->save();
            }

            DB::commit();
            return response()->json([
                'status' => 1,
                'message' => 'Successfully Submitted.'
            ]);
        }catch (\Exception $exception) {
            return response()->json([
                'status' => 0,
                'message' => 'Something went wrong! '.$exception->getMessage()
            ],500);
        }
    }

    public function tractorDemonstrationList(Request $request){
        $area_id = $request->area_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $TractorDemonstrationRecordEntry = TractorDemonstrationRecordEntry::query()->orderBy('id','desc')->with('participant_info','trail_report','model_image','area','territory');
          if (!empty($area_id)){
              $TractorDemonstrationRecordEntry = $TractorDemonstrationRecordEntry->where('area_id',$area_id);
          }
          if (!empty($from_date) && !empty($to_date)){
              $from_date = date('Y-m-d',strtotime($request->from_date));
              $to_date = date('Y-m-d',strtotime($request->to_date));
              $TractorDemonstrationRecordEntry = $TractorDemonstrationRecordEntry->whereBetween('date',[$from_date,$to_date]);
          }
        $TractorDemonstrationRecordEntry = $TractorDemonstrationRecordEntry->paginate(20);
        return new TractorDemonstrationCollection($TractorDemonstrationRecordEntry);
    }
}
