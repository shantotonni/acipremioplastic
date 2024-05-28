<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalesInquiryCollection;
use App\Http\Resources\SSRExpenseCollection;
use App\Http\Resources\SSRExpenseResource;
use App\SaleInquiry;
use App\SSRExpense;
use App\UserToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class SalesInquiryController extends Controller
{
    public function storeSalesInquiry(Request $request)
    {
        $validator = validator::make($request->all(),[
           'MotorsMSRCode' => 'required',
           'InquiryTypeId' => 'required',
           'ProductCode' => 'required',
           'OccupationId' => 'required',
           'village_name' => 'required',
           'customer_brand_model' => 'required',
           'inquiry_name' => 'required',
           'inquiry_mobile' => 'required',
        //    'next_visit_date' => 'required',
        //    'expected_delivery_date' => 'required',
        ]);

        $token = str_replace("token ","",$request->header('Authorization'));
        $user_token = UserToken::where("token",$token)->first();
        if(!$user_token){ return response()->json(['error'=>"Unauthorized"],401);}

        if ($validator->fails()) {
            $responseArr['message'] = $validator->errors();;
            return response()->json($responseArr, 400);
        }

        $sales_inquiry = new SaleInquiry();
        $sales_inquiry->MotorsMSRCode           = $request->MotorsMSRCode;
        $sales_inquiry->InquiryTypeId           = $request->InquiryTypeId;
        $sales_inquiry->ProductCode             = $request->ProductCode;
        $sales_inquiry->UseTypeId               = $request->UseTypeId;
        $sales_inquiry->ImplementId             = $request->ImplementId;
        $sales_inquiry->VisitResultId           = $request->VisitResultId;
        $sales_inquiry->OccupationId            = $request->OccupationId;
        $sales_inquiry->village_name            = $request->village_name;
        $sales_inquiry->customer_brand_model    = $request->customer_brand_model;
        $sales_inquiry->inquiry_name            = $request->inquiry_name;
        $sales_inquiry->inquiry_mobile          = $request->inquiry_mobile;
        $sales_inquiry->inquirytype             = $request->inquiry_type;
        $sales_inquiry->next_visit_date         = $request->next_visit_date;
        $sales_inquiry->expected_delivery_date  = $request->expected_delivery_date;
        $sales_inquiry->ssr_id  = $user_token->user_id;
        $sales_inquiry->quantity  = $request->quantity;
        $sales_inquiry->save();

        return response()->json(['message'=>'Successfully Inserted'],200);

    }

    public function getSalesInquiryByUpazillaCode(Request $request){

        $upazilla_code = $request->all();
        $upazilla_code = collect($upazilla_code);
        $code = $upazilla_code->pluck('upazilla_code');
        $sales_inquiry = SaleInquiry::whereIn('MotorsMSRCode',$code)->whereNull('reference_no')->with(['inquiryType','useType','implement','visitResult','occupation','ssr'])->get();
        return new SalesInquiryCollection($sales_inquiry);
    }
    
    public function updateSalesInquiryByReferenceNumber(Request $request){

        $sales_inquiry = SaleInquiry::findOrFail($request->id);
        $sales_inquiry->reference_no = $request->reference_number;
        $sales_inquiry->excepted_quantity = $request->quantity;
        $sales_inquiry->save();

        return response()->json([
            'sales_inquiry' => $sales_inquiry
        ], 200);
    }

    public function ssrExpense(Request $request){
        $expense = SSRExpense::where('user_id',$request->user_id)->where('period',$request->period)->where('bike_no',$request->bike_no)->first();

        if (empty($expense)){
            if ($request->hasFile('opening_image')) {
                $file = $request->file('opening_image');
                $opening_image_name = time().'.' . uniqid().'.'.$file->getClientOriginalExtension();
                Image::make($file)->resize(500,600)->save(public_path('ssr_expense/').$opening_image_name);
            } else {
                return response()->json(['message' => 'Opening Image Not Found','status'=>400], 400);
            }

            $expense = new SSRExpense();
            $expense->user_id = $request->user_id;
            $expense->opening_km = $request->opening_km;
            $expense->bike_no = $request->bike_no;
            $expense->period = $request->period;
            $expense->opening_image = $opening_image_name;
        }else{
            if ($request->hasFile('closing_image')) {
                $file = $request->file('closing_image');
                $closing_image_name = time().'.' . uniqid().'.'.$file->getClientOriginalExtension();
                Image::make($file)->resize(500,600)->save(public_path('ssr_expense/').$closing_image_name);
            } else {
                return response()->json(['message' => 'Closing Image Not Found','status'=>400], 400);
            }
            $expense->closing_km = $request->closing_km;
            $expense->closing_image = $closing_image_name;
        }

        $expense->save();
        return response()->json([
            'status'=>200,
            'message'=>'Successfully Inserted'
        ],200);
    }

    public function getSSRExpenseByUserAndPeriod(Request $request){
        $expense = SSRExpense::where('user_id',$request->user_id)->where('period',$request->period)->first();
        if (empty($expense)){
            return response()->json([
                'status'=>200,
                'data'=>null,
            ],200);
        }
        return new SSRExpenseResource($expense);
    }

    public function getAllSSRSalesInquiryList(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $user_token = UserToken::where("token",$token)->first();
        if(!$user_token){ return response()->json(['error'=>"Unauthorized"],401);}

        $sales_inquiry = SaleInquiry::where('ssr_id',$user_token->user_id)->with(['inquiryType','useType','implement','visitResult','occupation'])->get();
        return new SalesInquiryCollection($sales_inquiry);
    }

}
