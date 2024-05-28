<?php

namespace App\Http\Controllers;
use App\Exports\OrderExport;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function orderList(Request $request){
        $from = '';
        $to = '';
        $order = Order::query();
        if ($request->has('from_date') && $request->has('to_date')) {
            $from = date('Y-m-d',strtotime($request->from_date));
            $to = date('Y-m-d',strtotime($request->to_date));
            $order = $order->whereBetween('created_at',[$from,$to]);
            if ($request->order_status) {
                $order = $order->where('order_status',$request->order_status);
            }
        }elseif ($request->has('order_status')){
            $order = $order->where('order_status',$request->order_status);
        }

        $orders = $order->with('orderProduct','customer')->orderBy('id','desc')->paginate(10);
        return view('order.list',compact('orders','from','to'));
    }

    public function orderDetails($id){
        $order = Order::where('id',$id)->with('orderProduct','customer')->first();
        return view('order.order_details',compact('order'));
    }

    public function orderStatusChange(Request $request){
        $order = Order::where('id',$request->order_id)->first();
        $order->order_status = $request->order_status;
        $order->save();
        Session::flash("success", "Status Change Successfully !");
        return redirect()->route('order.details',$request->order_id);
    }

    public function orderDelete($id){
        $order = Order::where('id',$id)->with('orderProduct')->first();
        foreach ($order->orderProduct as $product){
            $product->delete();
        }
        $order->delete();
        return response()->json([
            'status'=>1,
            'msg'=>'Order Deleted Successfully'
        ]);
    }

    public function exportOrders(Request $request)
    {
        return Excel::download(new OrderExport($request) ,'order_list.xlsx');
    }

}
