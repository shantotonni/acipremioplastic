<?php

namespace App\Http\Controllers;

use App\Model\AddressType;
use App\Model\Coupon;
use App\Model\CouponLog;
use App\Model\CustomerAddress;
use App\Model\Invoice;
use App\Model\InvoiceDetail;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except'=>['districtWiseThana']]);
    }

    public function checkout(Request $request){
        $total_amount = str_replace(',','',Cart::subtotal());
        $total_amount = (int)$total_amount;
        if ($total_amount >= 1000) {
            if (!empty(Cart::content()) && count(Cart::content()) > 0){
                $customer = User::with('customerAddressTwo')->find(Auth::id());
                $districts = DB::select(DB::raw('SELECT * FROM vDistrict'));
                return view('checkout',compact('customer','districts'));
            }else{
                return redirect()->route('home');
            }
        }else{
            return redirect()->route('cart')->with([
                'message' => 'Minimum Purchase 1000 Tk',
                'alert-type' => 'warning',
                'showModal' => true
            ]);
        }
    }

    public function districtWiseThana(Request $request){
        $district = explode('-',$request->district);

        $thanas = DB::select(DB::raw("SELECT * FROM vUpazilla where DistrictCode='$district[0]'"));
        $string = '';
        $string .= "<option value=''>".'Select Thana'. "</option>";
        foreach($thanas as $value){
            $string .= "<option value='".$value->UpazillaCode.'-'.$value->UpazillaName."'>". ucfirst($value->UpazillaName) . "</option>";
        }

        return response()->json($string);
    }

    public function checkoutConfirm(Request $request){

        $validator = $this->validate($request,[
            'FirstName'=>'required',
            'CustomerMobileNo'=>'required',
            'CustomerEmail'=>'required',
            'DeliveryAddress'=>'required',
        ]);

        $district = explode('-',$request->District);
        $thana = explode('-',$request->Thana);

        $data = [];
        $data[] = [
            'AddressTypeID' =>$request->AddressTypeID2,
            'AddressID' =>$request->AddressID,
            'CustomerName' =>$request->FirstName .' '.$request->LastName,
            'CustomerMobileNo' =>$request->CustomerMobileNo,
            'CustomerEmail' =>$request->CustomerEmail,
            'DistrictCode' =>$district[0],
            'DistrictName' =>$district[1],
            'ThanaCode' =>$thana[0],
            'ThanaName' =>$thana[1],
            'DeliveryAddress' => $request->DeliveryAddress,
        ];

        return view('checkout_confirm',compact('data'));
    }

    public function checkoutStore(Request $request){
        $total_amount = str_replace(',','',Cart::subtotal());
        $total_amount = (int)$total_amount;
        if ($total_amount <= 1000){
            return redirect()->route('checkout.confirm');
        }

        $project_id = config('app.project_id');
        $ip = $_SERVER['REMOTE_ADDR'];

        $coupon_offer = session()->get('coupon_offer');

        if ($coupon_offer){
            $coupon_offer = session()->get('coupon_offer')['CouponCode'];
            $coupon = Coupon::where('CouponCode', $coupon_offer)->where('ProjectID',$project_id)->first();
            $coupon->Sold = $coupon->Sold+1;
            $coupon->save();

            $coupon_user = new CouponLog();
            $coupon_user->UserId = Auth::user()->CustomerID;
            $coupon_user->ProjectID = $project_id;
            $coupon_user->CouponID = $coupon->CouponID;
            $coupon_user->CouponCode = $coupon->CouponCode;
            $coupon_user->Mobile = Auth::user()->CustomerMobileNo;
            $coupon_user->CouponName = $coupon->CouponName;
            $coupon_user->Offer = $coupon->Offer;
            $coupon_user->OfferType = $coupon->OfferType;
            $coupon_user->CreatedAt = Carbon::now();
            $coupon_user->UpdatedAt = Carbon::now();
            $coupon_user->ValidUpto = Carbon::now();
            $coupon_user->Used = 1;
            $coupon_user->save();

            $CouponID = $coupon->CouponID;
        }

        $mrp_total = 0;
        foreach(Cart::content() as $item){
            $mrp_total += $item->options->ItemPrice * $item->qty;
        }

        $coupon_offer = session()->get('coupon_offer');
        if (!empty($coupon_offer)){
            $offer = session()->get('coupon_offer')['offer'];
            $offer_amount = (str_replace(',','',$mrp_total) * $offer) /100;
            $subtotal = $mrp_total - $offer_amount;
            $total_price = $mrp_total - $offer_amount;
        }else{
            $subtotal = Cart::subtotal();
            $total_price = Cart::total();
        }

        $invoice = new Invoice();
        $invoice->ProjectID = $project_id;
        $invoice->CouponID = isset($coupon) ? $coupon->CouponID : '';
        $invoice->InvoicePeriod = Carbon::now()->format('Ym');
        $invoice->InvoiceDate = Carbon::now();
        $invoice->CustomerID = Auth::user()->CustomerID;
        $invoice->CouponID = isset($CouponID) ? $CouponID : null;
        $invoice->CustomerName = $request->CustomerName;
        $invoice->CustomerMobileNo = $request->CustomerMobileNo;
        $invoice->CustomerEmail = $request->CustomerEmail;
        $invoice->AddressID = isset($request->AddressID) ? $request->AddressID : '';
        $invoice->DistrictCode = $request->DistrictCode;
        $invoice->DistrictName = $request->DistrictName;
        $invoice->ThanaCode = $request->ThanaCode;
        $invoice->ThanaName = $request->ThanaName;

        $invoice->DeliveryAddress = $request->DeliveryAddress;

        $invoice->DiscountAmount = isset($request->DiscountAmount) ? $request->DiscountAmount : 0;
//        $invoice->TotalAmount = str_replace(',','',Cart::total());
//        $invoice->GrandTotal = str_replace(',','',Cart::total()) - (isset($request->DiscountAmount) ? $request->DiscountAmount : 0);
        $invoice->TotalAmount = str_replace(',','',$subtotal);
        $invoice->GrandTotal = str_replace(',','',$total_price);
        $invoice->DiscountID = 1;
        $invoice->DeliveryCharge = 0;
        $invoice->Remark = 'Remark';
        $invoice->SupplierID = 1;
        $invoice->PaymentMethodId = 1;
        $invoice->IpAddress = $ip;
        $invoice->InvStatusID = 1;
        $invoice->ShipDate = '';
        $invoice->ShippersID = '';

        if ($invoice->save()){
            foreach(Cart::content() as $item){
                $coupon_offer = session()->get('coupon_offer');
                if (!empty($coupon_offer)){
                    $amount = $item->options->ItemPrice;
                    $offer = session()->get('coupon_offer')['offer'];
                    $offer_amount = (str_replace(',','',$amount) * $offer) /100;
                    $item_price = $item->options->ItemPrice;
                    $item_final_price = $item->options->ItemPrice - $offer_amount;
                }else{
                    $item_price = $item->price;
                    $item_final_price = $item->price;
                }

                $invoice_details =new InvoiceDetail();
                $invoice_details->InvoiceNo = $invoice->InvoiceNo;
                $invoice_details->ProductCode = $item->id;
                $invoice_details->ProductName = $item->name;
                $invoice_details->Quantity = $item->qty;
                $invoice_details->DeliveryQuantity = $item->qty;
                $invoice_details->ItemPrice = $item_price;

                $invoice_details->VAT = 0;
                $invoice_details->Discount = $offer_amount;
                $invoice_details->ItemFinalPrice = $item_final_price;
                $invoice_details->save();
            }

            if(!empty(session()->get('coupon_offer')['CouponCode'])){
                session()->forget('coupon_offer');
            }

            Cart::instance('default')->destroy();
            Toastr::success('Order Successfully Completed' ,'Success');
            return redirect()->route('invoice.details',$invoice->InvoiceNo);
        }
    }

}
