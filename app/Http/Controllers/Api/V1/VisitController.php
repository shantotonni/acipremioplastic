<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\VisitCollection;
use App\InvoiceCustomerList;
use App\Result;
use App\UserToken;
use App\Visit;
use App\VisitType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VisitController extends Controller
{
    public function getVisitResultData(){
        $visit_type = VisitType::all();
        $result = Result::all();
        return response()->json([
            'visit_type'=>$visit_type,
            'result'=>$result,
        ]);
    }

    public function visitList(){
        $visits = Visit::with('upazilla','visit_type','result','user')->get();
        return new VisitCollection($visits);
    }

    public function visitStore(Request $request){

        $validator =  Validator::make($request->all(),[
            'upazilla_id' => 'required',
            'visit_type_id' => 'required',
            'result_id' => 'required',
            'village_name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                "message" => $validator->errors(),
            ], 422);
        }

        $token = str_replace("token ","",$request->header('Authorization'));
        $user_token = UserToken::where("token",$token)->first();
        if(!$user_token){ return response()->json(['error'=>"Unauthorized"],401);}

        $visit = new Visit();
        $visit->upazilla_id = $request->upazilla_id;
        $visit->visit_type_id = $request->visit_type_id;
        $visit->result_id = $request->result_id;
        $visit->village_name = $request->village_name;
        $visit->purpose = $request->purpose;
        $visit->person_name = $request->person_name;
        $visit->person_mobile = $request->person_mobile;
        $visit->ssr_id = $user_token->user_id;
        $visit->save();

        return response()->json([
            'status'=>200,
            'message'=>'Successfully Added Visit'
        ]);
    }

    public function customerList(Request $request){
        $search    = $request->search;

//        $customers = DB::connection('MotorBrInvoiceMirror')->select("SELECT
//                C.CustomerCode, C.CustomerName1, C.Address1, C.Address2,
//                C.Mobile,D.DistrictName,UN.UpazilaName, B.BatchNo AS ChassisNo,
//                PB.BrandName, P.ProductName, P.Model, A.InvoiceDate, 0
//            FROM SALESVIEW A
//                INNER JOIN Customer C
//                    ON A.CustomerCode = C.CustomerCode
//                INNER JOIN District D
//                    ON D.DistrictCode = C.DistrictCode
//                LEFT JOIN UpazilaNew UN
//                    ON UN.UpazilaCode = C.ThanaCode
//                INNER JOIN Product P
//                    ON A.ProductCode = P.ProductCode
//                INNER JOIN ProdBrand PB
//                    ON P.BrandCode = PB.BrandCode
//                INNER JOIN InvoiceDetailsBatch B
//                    ON A.Invoiceno = B.Invoiceno
//            WHERE A.Business IN('Q','W')
//            AND A.InvoiceDate >= '2020-01-01 00:00:00.000'
//            AND (a.InvoiceType <> 'A')
//            AND B.BatchNo <> ''
//            AND LEN(B.BatchNo) >= 10
//            AND PB.BrandName not like '%Rotavator%'
//            AND (A.CustomerCode ='$search' OR B.BatchNo ='$search')
//            GROUP BY C.CustomerCode, C.CustomerName1, C.Address1, C.Address2,
//                C.Mobile,D.DistrictName,UN.UpazilaName, B.BatchNo,
//                PB.BrandName, P.ProductName, P.Model, A.InvoiceDate");

        $customers = DB::connection('MotorBrInvoiceMirror')->table('SALESVIEW','A')
            ->select('C.CustomerCode','C.CustomerName1','C.Address1','C.Address2','C.Mobile','D.DistrictName','UN.UpazilaName','B.BatchNo as ChassisNo','PB.BrandName','P.ProductName','P.Model','A.InvoiceDate','A.Business')
            ->join('Customer as C','A.CustomerCode','=','C.CustomerCode')
            ->join('District as D','D.DistrictCode','=','C.DistrictCode')
            ->leftJoin('UpazilaNew as UN','UN.UpazilaCode','=','C.ThanaCode')
            ->join('Product as P','A.ProductCode','=','P.ProductCode')
            ->join('ProdBrand as PB','P.BrandCode','=','PB.BrandCode')
            ->join('InvoiceDetailsBatch as B','A.Invoiceno','=','B.Invoiceno')
            ->whereIn('A.Business',['Q','W'])
            ->where('A.InvoiceDate','>=','2010-01-01 00:00:00.000')
            ->where('A.InvoiceType','!=','A')
            ->where('B.BatchNo','!=','')
            ->whereRaw('LEN(B.BatchNo) >= 10')
            ->where('PB.BrandName','not like','%Rotavator%')
            ->where(function ($query) use($search){
                $query->where('A.CustomerCode','like','%'.$search.'%');
                $query->orWhere('B.BatchNo','like','%'.$search.'%');
            })
            ->groupBy('C.CustomerCode','C.CustomerName1','C.Address1','C.Address2','C.Mobile','D.DistrictName','UN.UpazilaName','B.BatchNo','PB.BrandName','P.ProductName','P.Model','A.InvoiceDate','A.Business')
            ->paginate(20);

        return response()->json([
            'status'=>200,
            'customers' => $customers
        ]);
    }
}
