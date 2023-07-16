<?php

namespace App\Http\Controllers;

use App\Model\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function invoiceDetails($InvoiceNo){
        $invoice = Invoice::where('InvoiceNo',$InvoiceNo)->with('invoiceDetail','invoiceStatus','coupon')->first();
        return view('invoice_details',compact('invoice'));
    }
}
