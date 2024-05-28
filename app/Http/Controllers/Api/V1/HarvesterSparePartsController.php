<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SparePartsDetailsResource;
use App\Http\Resources\SparePartsResource;
use App\MotorSparePartsMirrorProduct;
use App\MotorSparePartsMirrorStockBatch;
use Illuminate\Http\Request;

class HarvesterSparePartsController extends Controller
{
    public function sparePartsList(Request $request){

        $spare = MotorSparePartsMirrorProduct::query()->where('Business','W','Q');
        if ($request->has('search')) {
            $query = $request->search;
            $spare = $spare->where('ProductCode','LIKE','%'.$query.'%')->orWhere('ProductName','LIKE','%'.$query.'%');
        }
        $spare = $spare->with('stock')->where('Business','W','Q')->paginate(20);
        return SparePartsResource::collection($spare);
    }

    public function sparePartsDetails(Request $request){
        $stock_details = MotorSparePartsMirrorStockBatch::where('ProductCode',$request->ProductCode)->with('depot')->get();
        return SparePartsDetailsResource::collection($stock_details);
    }
}
