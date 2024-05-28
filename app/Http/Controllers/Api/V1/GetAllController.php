<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;

use App\Implement;
use App\InquiryProduct;
use App\InquiryType;
use App\Occupation;
use App\ProductModel;
use App\Upazila;
use App\UseType;
use App\VisitResult;
use Illuminate\Http\Request;
use App\Http\Resources\GetAll as GetAllResource;
use App\Product;
use App\ServiceType;
use App\CallType;
use App\User;
use App\UserToken;
use App\Http\Resources\User as UserResource;
use App\JobCard;
use App\Target;
use App\UserTerritory;

class GetAllController extends Controller{
    
    public function index(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $user_token = UserToken::where('token',$token)->first();
        if(!$user_token){ return response()->json(['errors'=>"Unauthorized"],401);}
        $user = User::find($user_token->user_id);
        
        return response()->json(['data'=>[
           'user_info'=>$user,
           'products'=>Product::all(),
           'product_model'=>ProductModel::all(),
           'call_types'=>CallType::all(),
           'service_types'=>ServiceType::all(),
           'perticipants'=>$this->userOfThisArea($user),
       ]]);
    }

    public function use_info(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $use_token = UserToken::where('token',$token)->first();
        $use = User::find($use_token->use_id);
        return new UserResource($use);
    }

    private function userOfThisTerritory($user){
         $user_territories = UserTerritory::where('territory_id',$user->user_territory->territory_id)->where('user_id','!=',$user->id)->pluck('user_id');
         return User::whereIn('id',$user_territories)->get();
    }

    private function userOfThisArea($user){
        if ($user->user_territory) {
            $area_id = $user->user_territory->territory->area_id;
            return UserTerritory::select('users.id','users.username','users.name')
                ->join('territories','territories.id','user_territories.territory_id')
                ->join('users','users.id','user_territories.user_id')
                ->where('territories.area_id',$area_id)
                ->where('users.id','!=',$user->id)
                ->get();
        }else{
            return [];
        }

    }

    public function get_technitian_dashboard_info(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $user_token = UserToken::where('token',$token)->first();
        if(!$user_token){ return response()->json(['errors'=>"Unauthorized"],401);}
        $user = User::find($user_token->user_id); 
        
        return response()->json(['data'=>[
           'this_month_target'=>$this->get_technitian_this_month_target($user->id),
           'this_month_done'=>$this->get_technitian_this_month_done($user->id),
           'this_year_target'=>$this->get_technitian_year_target($user->id),
           'this_year_done'=>$this->get_technitian_year_done($user->id),
       ]]);
    }

    private function get_technitian_this_month_target($user_id){
       $t = Target::whereMonth('date',date('m'))->where('technitian_id',$user_id)->first();
       return $t ? $t->total : 0;
    }

    private function get_technitian_this_month_done($user_id){
      return JobCard::join('job_card_details','job_card_details.job_card_id','job_cards.id')
                      ->whereMonth('job_cards.created_at',date('m'))
                      ->where('job_card_details.user_id',$user_id)
                      ->count();
    }

    private function get_technitian_this_month_warranty_service_target($user_id){
      $t = Target::whereMonth('date',date('m'))->where('technitian_id',$user_id)->first();
      return $t ? $t->warranty_master_total : 0;
    }

    private function get_technitian_this_month_warranty_service_done($user_id){
      return JobCard::join('job_card_details','job_card_details.job_card_id','job_cards.id')
                      ->whereMonth('job_cards.created_at',date('m'))
                      ->where('job_card_details.user_id',$user_id)
                      ->count();
    }

    private function get_technitian_this_month_post_warranty_service_target($user_id){
      $t = Target::whereMonth('date',date('m'))->where('technitian_id',$user_id)->first();
      return $t ? $t->post_warranty_master_total : 0;
    }

    private function get_technitian_this_month_post_warranty_service_done($user_id){
      return JobCard::join('job_card_details','job_card_details.job_card_id','job_cards.id')
                      ->whereMonth('job_cards.created_at',date('m'))
                      ->where('job_card_details.user_id',$user_id)
                      ->count();
    }
    
    private function get_technitian_year_target($user_id){
        return 200;
     }
 
     private function get_technitian_year_done($user_id){
       return 100;
     }

     public function getAllSalesInquiry(){
         return response()->json([
             'upazilla_list'=>Upazila::select('id','name','name_bn','MotorsMSRCode')->get(),
             'inquiry_type_list'=>InquiryType::select('InquiryTypeId','InquiryTypeName','AppName')->where('AppName','MSR_InquiryAPP')->get(),
             'product_list'=>$this->product(),
             'use_type'=>UseType::all(),
             'implement'=>Implement::where('Active','Y')->get(),
             'visit_result'=>VisitResult::where('AppName','MRS_InquiryAPP')->get(),
             'occupation'=>Occupation::all(),
         ]);
     }

     public function product(){
        return InquiryProduct::select('ProductCode','Product.InquiryTypeId','ProductName','ProductPrice','BrandName','Active','InquiryTypeName','AppName','ProductBasePrice')
            ->where('InquiryType.AppName','MSR_InquiryAPP')
            ->where('ProductName','!=','')
            ->whereNotNull('ProductName')
            ->where('Active','Y')
            ->join('InquiryType','Product.InquiryTypeId','=','InquiryType.InquiryTypeId')
            ->get();
     }
}
