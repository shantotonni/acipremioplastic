<?php

namespace App\Http\Controllers;

use App\Model\Invoice;
use Illuminate\Http\Request;

class OrderTrackController extends Controller
{
    public function orderTrack(){
        return view('track_order');
    }

    public function orderTrackPost(Request $request){
        $this->validate($request,[
            'InvoiceNo' =>'required|numeric',
            'CustomerMobileNo' =>'required',
        ]);

        $InvoiceNo = $request->InvoiceNo;
        $CustomerMobileNo = $request->CustomerMobileNo;

        $invoice = Invoice::where('InvoiceNo',$InvoiceNo)->where('CustomerMobileNo',$CustomerMobileNo)->first();

        if ($invoice) {
            return view('track_order',compact('invoice','CustomerMobileNo'));
        }else{
            return redirect()->back();
        }

    }
}
