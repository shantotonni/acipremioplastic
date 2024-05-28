<?php

namespace App\Http\Controllers\Api\V1;

use App\CustomerToken;
use App\Http\Controllers\Controller;
use App\Http\Resources\JobCollection;
use App\Http\Resources\JobCard as JobCardResource;
use App\JobCard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerJobCardController extends Controller
{
    public function customerJobList(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $customer_token = CustomerToken::where("token",$token)->first();
        if(!$customer_token){ return response()->json(['error'=>"Unauthorized"],401);}

        $job_list = JobCard::where('customer_id',$customer_token->customer_id)->with('service_type','customer','section','area','product','technitian','call_type')->get();
        return JobCardResource::collection($job_list);
    }
    public function customerStatus(Request $request){
        $current_date = Carbon::now()->format('Y-m-d');
        $cuustomer_code = $request->CustomerCode;
        $business = $request->Business;
        if (empty($cuustomer_code)){
            return response()->json([
                'status'=>1,
                'message'=>'Customer Code Not Found',
            ]);
        }
        $sql = "exec sp_CustomerStatus '%', 'Jan 1 2005', '$current_date', 'RPT', 'N','0', '$business', '$cuustomer_code'";
        $conn = DB::connection('MotorBrInvoiceMirror');
        $pdo = $conn->getPdo()->prepare($sql);
        $pdo->execute();
        $res = array();
        do {
            $rows = $pdo->fetchAll(\PDO::FETCH_ASSOC);
            $res[] = $rows;
        } while ($pdo->nextRowset());
        if (!empty($res)){
            $result = [];
            //return $customerStatus = $res[0];
            //$customerStatus2 = $res[1];
            foreach ($res[0] as $item){
                if ($item['DueInstNo'] == $item['CollectInstNo']){
                    $DueInstNo = 0;
                }else{
                    $DueInstNo = $item['NoOfInstallment'] - $item['OverDueInstNo'];
                }
                $result = [
                  'OverDueTaka' => $item['OverDueTaka'],
                  'OverDueInstNo' => $item['OverDueInstNo'],
                  'DueAmount' => $item['OutstandingReturn'],
                  'DueInstNo' => $DueInstNo,
                ];
            }
        }
        return response()->json([
           'status'=>1,
           'data'=> $result,
            //'data2'=>$customerStatus2
        ]);
    }

}
