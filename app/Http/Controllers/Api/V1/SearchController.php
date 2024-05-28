<?php

namespace App\Http\Controllers\Api\V1;

use App\ApprovedChassis;
use App\Http\Controllers\Controller;
use App\ImportHarvester;
use App\StockBatch;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function chassisNumberWiseHarvesterSearch(Request $request){

        $search = $request->input('search');

        $chesis = ImportHarvester::query()
            ->where('chesis', 'LIKE', "%{$search}%")
            ->get();

        return response()->json([
            'status'=>200,
            'result'=>$chesis
        ]);

    }

    public function findChassisNumber(Request $request){
        $search = $request->input('search');
        $chassis = StockBatch::where('BatchNo',$search)->first();
        //return $chassis;

        if(empty($chassis)){
            $chassis1 = ApprovedChassis::where('chassis_no',$search)->first();
            if(!empty($chassis1)){
                $chassis['BatchNo'] = $chassis1->chassis_no;
            }
        }

        return response()->json([
            'status'=>200,
            'result'=>$chassis
        ]);
    }
}
