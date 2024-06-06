<?php

namespace App\Http\Controllers;

use App\Model\Coupon;
use App\Model\CouponLog;
use App\Model\Invoice;
use App\Model\Product;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferProductController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function productWithOffer($id){
        $coupon_log = CouponLog::where('CouponID',$id)->orderBy('CreatedAt','desc')->first();
        $user_id = Auth::user()->CustomerID;
        if (!empty($user_id)) {
            if ($user_id == $coupon_log->UserId) {
                $coupon = Coupon::where('CouponCode',$coupon_log->CouponCode)->with('couponProduct.product')->first();
                Toastr::success('You Can Buy Only One Any Product Given This List.' ,'Reminder',['timeOut' => 1000000]);
                return view('offer_product',compact('coupon'));
            }else{
                Toastr::error('Something went wrong' ,'Error');
                return redirect()->back();
            }
        }else{
            return redirect()->route('home');
        }

    }

    public function startClaim(Request $request){

        $project_id = config('app.project_id');
        $created_at = Carbon::now();

        $coupon_log = new CouponLog();
        $coupon_log->ProjectID = $project_id;
        $coupon_log->UserId = Auth::user()->CustomerID;
        $coupon_log->CouponCode = $request->couponCode;
        $coupon_log->Mobile = Auth::user()->CustomerMobileNo;
        $coupon_log->CouponName = $request->offer_name;
        $coupon_log->Offer = $request->offer;
        $coupon_log->OfferType = "percentage";
        $coupon_log->CreatedAt = $created_at;
        $coupon_log->UpdatedAt = $created_at;
        $coupon_log->ValidUpto = $created_at->addMinutes(30);
        $coupon_log->save();
        return response()->json(['success'=>'Claim Successfully Generated','CouponID' =>$coupon_log->CouponID]);
    }

    public function applyCoupon(Request $request){
        $project_id = config('app.project_id');
        $coupon = Coupon::where('CouponCode', $request->coupon_code)->where('ProjectID',$project_id)->first();
        if (!$coupon){
            Toastr::error('Invalid coupon code. Please try again.' ,'Error');
            return redirect()->back();
        }
        $coupon_exist = CouponLog::where('CouponCode', $request->coupon_code)->where('UserId',Auth::user()->CustomerID)->where('ProjectID',$project_id)->where('Used',1)->exists();

        $cart_item = Cart::content();
        //for same category product check
        if ($coupon->CategoryId){
            foreach ($cart_item as $item){
                $product = Product::where('ProductCode',$item->id)->first();
                if ($product->CategoryId != $coupon->CategoryId){
                    Toastr::error('Please Add Same Category Product.' ,'Error');
                    return redirect()->back();
                }
            }
        }

        if (session()->get('coupon_offer')){
            $exist_session_coupon = session()->get('coupon_offer')['CouponCode'];
            $request_coupon = $request->coupon_code;
            if ($exist_session_coupon == $request_coupon){
                Toastr::error('Coupon is already used.' ,'Error');
                return redirect()->back();
            }
        }

        if ($coupon_exist) {
            Toastr::error('Coupon is already used.' ,'Error');
            return redirect()->back();
        }else{
            if (strcmp($coupon->CouponCode,$request->coupon_code) != 0){
                Toastr::error('Invalid coupon code. Please try again.' ,'Error');
                return redirect()->back();
            }else {
                $coupon_expired = Coupon::where('CouponCode', $request->coupon_code)->whereDate('CouponExpiredDate' ,'>=', Carbon::now())->where('ProjectID',$project_id)->first();
                if (!$coupon_expired){
                    Toastr::error('Coupon Already Expired.' ,'Error');
                    return redirect()->back();
                }else {
                    if ($coupon->Sold >= $coupon->Limit) {
                        Toastr::error('Coupon Limit ends.' ,'Error');
                        return redirect()->back();
                    }else {
                        $amount = str_replace(',','',Cart::subtotal());
                        if ($coupon->CouponAmount > $amount){
                            Toastr::error('You Need To Buy above '.$coupon->CouponAmount.' If you want apply the coupon' ,'Error');
                            return redirect()->back();
                        }else{
                            if ($coupon->Business == 'H'){
                                //for multiple Product Discount check
                                foreach ($cart_item as $item){
                                    $product = Product::where('ProductCode',$item->id)->first();
                                    if ($product->Discount > $coupon->Offer){
                                        Toastr::error('This coupon code is not applicable for this item.' ,'Error');
                                        return redirect()->back();
                                    }
                                }

                                foreach (Cart::content() as $item){
                                    Cart::update($item->rowId, ['price' => $item->options->ItemPrice]);
                                }
                            }
                            session()->put('coupon_offer',[
                                'CouponCode'=>$coupon->CouponCode,
                                'offer'=>$coupon->Offer,
                            ]);

                            Toastr::success('Coupon has been applied!' ,'Success');
                            return redirect()->back();
                        }
                    }
                }
            }
        }
    }

    public function applyCouponBackup(Request $request){
        $project_id = config('app.project_id');
        $coupon = Coupon::where('CouponCode', $request->coupon_code)->where('ProjectID',$project_id)->first();
        $coupon_exist = CouponLog::where('CouponCode', $request->coupon_code)->where('UserId',Auth::user()->CustomerID)->where('ProjectID',$project_id)->exists();

        if ($coupon_exist) {
            Toastr::error('Coupon is already used.' ,'Error');
            return redirect()->back();
        }else{
            if (strcmp($coupon->CouponCode,$request->coupon_code) != 0){
                Toastr::error('Invalid coupon code. Please try again.' ,'Error');
                return redirect()->back();
            }else {
                $coupon_expired = Coupon::where('CouponCode', $request->coupon_code)->whereDate('CouponExpiredDate' ,'>=', Carbon::now())->where('ProjectID',$project_id)->first();
                if (!$coupon_expired){
                    Toastr::error('Coupon Already Expired.' ,'Error');
                    return redirect()->back();
                }else {
                    if ($coupon->Sold >= $coupon->Limit) {
                        Toastr::error('Coupon Limit ends.' ,'Error');
                        return redirect()->back();
                    }else {
                        // $amount = Cart::subtotal();
                        $amount = str_replace(',','',Cart::subtotal());
                        if ($coupon->CouponAmount > $amount){
                            Toastr::error('You Need To Buy above '.$coupon->CouponAmount.' If you want apply the coupon' ,'Error');
                            return redirect()->back();
                        }else{
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

                            session()->put('coupon_offer',[
                                'CouponCode'=>$coupon->CouponCode,
                                'offer'=>$coupon->Offer,
                            ]);

                            Toastr::success('Coupon has been applied!' ,'Success');
                            return redirect()->back();
                        }
                    }
                }
            }
        }
    }

}
