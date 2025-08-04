<?php

namespace App\Http\Controllers;

use App\Model\CouponLog;
use App\Model\GuestUser;
use App\Model\Invoice;
use App\Model\InvoiceDetail;
use App\Model\Product;
use App\Model\Stock;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class GustOrderController extends Controller
{
    public function guestOrder(Request $request){

        $this->validate($request,[
            'CustomerFirstName'=>'required',
            'CustomerMobileNo'=>'required',
            'District'=>'required',
            'Thana'=>'required',
            'DeliveryAddress'=>'required',
            'DateOfBirth'=>'required',
        ]);

        $district = explode('-',$request->District);
        $thana = explode('-',$request->Thana);

        $data = [];
        $data[] = [
            'AddressTypeID' =>$request->AddressTypeID2,
            'AddressID' =>$request->AddressID,
            'CustomerName' =>$request->CustomerFirstName .' '.$request->CustomerLastName,
            'DateOfBirth' =>$request->DateOfBirth,
            'CustomerMobileNo' =>$request->CustomerMobileNo,
            'CustomerEmail' =>$request->CustomerEmail,
            'DistrictCode' =>$district[0],
            'DistrictName' =>$district[1],
            'ThanaCode' =>$thana[0],
            'ThanaName' =>$thana[1],
            'DeliveryAddress' => $request->DeliveryAddress,
        ];

        return view('guest_checkout_confirm',compact('data'));

    }

    public function guestOrderStore(Request $request){

        $total_amount = str_replace(',','',Cart::subtotal());
        $total_amount = (int)$total_amount;
        if ($total_amount <= 1000) {
            Toastr::success('Minimum Purchase 1000 Tk' ,'Success');
            return redirect()->to('cart');
        }

        $project_id = config('app.project_id');
        $ip = $_SERVER['REMOTE_ADDR'];

        $spin_offer = session()->get('spin_offer')['CouponCode'];
        $coupon_offer = session()->get('coupon_offer')['CouponCode'];
        if ($spin_offer) {
            $couponCode = $spin_offer;
        }else {
            $couponCode = $coupon_offer;
        }

        $coupon_log = CouponLog::where('CouponCode',$couponCode)->where('ProjectID',$project_id)->first();
        if (!empty($coupon_log)) {
            $CouponID = $coupon_log->CouponID;
        }else {
            $CouponID = null;
        }

        $invoice = new Invoice();
        $invoice->ProjectID = $project_id;
        $invoice->InvoicePeriod = Carbon::now()->format('Ym');
        $invoice->InvoiceDate = Carbon::now();
        $invoice->CustomerID = '';
        $invoice->CouponID = $CouponID;
        $invoice->CustomerName = $request->CustomerName;
        $invoice->DateOfBirth = $request->DateOfBirth;
        $invoice->CustomerMobileNo = $request->CustomerMobileNo;
        $invoice->CustomerEmail = '';
        $invoice->AddressID = isset($request->AddressID) ? $request->AddressID : '';
        $invoice->DistrictCode = $request->DistrictCode;
        $invoice->DistrictName = $request->DistrictName;
        $invoice->ThanaCode = $request->ThanaCode;
        $invoice->ThanaName = $request->ThanaName;
        $invoice->DeliveryAddress = $request->DeliveryAddress;
        $invoice->DiscountAmount = isset($request->DiscountAmount) ? $request->DiscountAmount : 0;
        $invoice->TotalAmount = str_replace(',','',Cart::total());
        $invoice->GrandTotal = (str_replace(',','',Cart::total()) - (isset($request->DiscountAmount) ? $request->DiscountAmount : 0)) + $request->DeliveryCharge;
        $invoice->DiscountID = 1;
        $invoice->DeliveryCharge = $request->DeliveryCharge;
        $invoice->Remark = 'Remark';
        $invoice->SupplierID = 1;
        $invoice->PaymentMethodId = 1;
        $invoice->IpAddress = $ip;
        $invoice->InvStatusID = 1;
        $invoice->ShipDate = '';
        $invoice->ShippersID = '';


        if ($invoice->save()){
            foreach(Cart::content() as $item){
                $invoice_details                    = new InvoiceDetail();
                $invoice_details->InvoiceNo         = $invoice->InvoiceNo;
                $invoice_details->ProductCode       = $item->id;
                $invoice_details->ProductName       = $item->name;
                $invoice_details->Quantity          = $item->qty;
                $invoice_details->DeliveryQuantity  = $item->qty;
                $invoice_details->ItemPrice         = $item->price;
                $invoice_details->VAT               = Product::where('ProductCode',$item->id)->first()->VAT;
                $invoice_details->Discount          = isset($request->DiscountAmount) ? $request->DiscountAmount : 0;
                $invoice_details->ItemFinalPrice    = $item->price;
                $invoice_details->save();

                $stock = Stock::where('ProjectID',config('app.project_id'))
                    ->where('ProductCode',$item->id)
                    ->first();
                $stock->Opening = ($stock->Opening - $item->qty);
                $stock->Sales = $stock->Sales + $item->qty;
                $stock->save();
            }

            $guest_user                 = new GuestUser();
            $guest_user->customer_name  = $request->CustomerName;
            $guest_user->DateOfBirth    = $request->DateOfBirth;
            $guest_user->mobile         = $request->CustomerMobileNo;
            $guest_user->email          = $request->CustomerEmail;
            $guest_user->DeliveryAddress= $request->DeliveryAddress;
            $guest_user->District       = $request->DistrictName;
            $guest_user->Thana          = $request->ThanaName;
            $guest_user->InvoiceId      = $invoice->InvoiceNo;
            $guest_user->ProjectID      = $project_id;
            $guest_user->save();

            $smscontent="Your Order Has been Placed.Your Order Id: ".$invoice->InvoiceNo;
            $to = $request->CustomerMobileNo;
            $sId = '8809617615000';
            $applicationName = 'ACI Premio Plastics';
            $moduleName = 'Guest Order';
            $otherInfo = '';
            $userId = $project_id;
            $vendor = 'smsq';
            $message = $smscontent;
            $this->sendSmsQ($to, $sId, $applicationName, $moduleName, $otherInfo, $userId, $vendor, $message);

            if(!empty(session()->get('spin_offer')['CouponCode'])){
                $coupon_log->Used = 1;
                $coupon_log->save();
                session()->forget('spin_offer');
            }
            if(!empty(session()->get('coupon_offer')['CouponCode'])){
                session()->forget('coupon_offer');
            }

            Cart::instance('default')->destroy();
            Toastr::success('Order Successfully Completed' ,'Success');
            return redirect()->route('invoice.success');
        }
    }

    public static function sendSmsQ($to, $sId, $applicationName, $moduleName, $otherInfo, $userId, $vendor, $message)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.102.10/apps/api/send-sms/sms-master',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'To='.$to.'&SID='.$sId.'&ApplicationName='.urlencode($applicationName).'&ModuleName='.urlencode($moduleName).'&OtherInfo='.urlencode($otherInfo).'&userID='.$userId.'&Message='.$message.'&SmsVendor='.$vendor,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ));
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    function sendSms($receipient, $smstext) {
        $ip = "192.168.100.213";
        $userId = "cepl";
        $password = "Asdf1234";
        $smstext = urlencode($smstext);
        $smsUrl = "http://{$ip}/httpapi/sendsms?userId={$userId}&password={$password}&smsText=" . $smstext . "&commaSeperatedReceiverNumbers=" . $receipient;
        $smsUrl = preg_replace("/ /", "%20", $smsUrl);
        $response = file_get_contents($smsUrl);
        return json_decode($response);
    }

    public function invoiceSuccess(){
        return view('invoice_success');
    }
}
