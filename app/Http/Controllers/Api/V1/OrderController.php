<?php

namespace App\Http\Controllers\Api\V1;

use App\Customer;
use App\CustomerToken;
use App\District;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderProduct;
use App\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function customerOrderList(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $customer_token = CustomerToken::where("token",$token)->first();
        if(!$customer_token){ return response()->json(['error'=>"Unauthorized"],401);}

        $orders = Order::where('customer_id',$customer_token->customer_id)->with('orderProduct','orderProduct.part')->get();
        return \App\Http\Resources\Order::collection($orders);
    }

    public function order(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $customer_token = CustomerToken::where("token",$token)->first();
        if(!$customer_token){ return response()->json(['error'=>"Unauthorized"],401);}

        $this->validate($request,[
            "customer_id"=>"required",
            "district_id"=>"required",
            "upazila_id"=>"required",
            "delivery_address"=>"required",
        ]);

        DB::beginTransaction();

        try {
            $customer = Customer::where('id',$request->customer_id)->with('area')->first();
            $district = District::where('id',$request->district_id)->first();
            $upazila = Upazila::where('id',$request->upazila_id)->first();
            $products = $request->products;

            $order = new Order();
            $order->name = $customer->name;
            $order->code = $customer->code;
            $order->mobile = $customer->mobile;
            $order->customer_id = $customer->id;
            $order->customer_address = $customer->address;
            $order->delivery_address = $request->delivery_address;

            $order->area_name = $customer->area->name;
            $order->district_name = $district->name;
            $order->upazila_name = $upazila->name;
            $order->discount = 0;
            $order->delivery_charge = 0;
            $order->order_status = 'pending';

            $order->total_amount = $request->total_amount;
            $order->grand_total = $request->total_amount;

            if ($order->save()) {
                foreach ($products as $product){
                    $order_product = new OrderProduct();
                    $order_product->order_id = $order->id;
                    $order_product->product_id = $product['ProductId'];
                    $order_product->product_name = $product['ProductName'];
                    $order_product->quantity = $product['quantity'];
                    $order_product->item_price = $product['UnitPrice'];
                    $order_product->vat = 0;
                    $order_product->discount = 0;
                    $order_product->item_final_price = ($product['UnitPrice'] * $product['quantity']);
                    $order_product->save();
                }
                DB::commit();
                return response()->json([
                    'status' => 1,
                    'msg'=>'Order Store Successfully'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
    }
}
