<?php

namespace App\Http\Controllers;

use App\Model\Coupon;
use App\Model\CouponLog;
use App\Model\Product;
use App\Model\Stock;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(){
        //dd(Cart::content());
        return view('cart');
    }

    public function cartStore(Request $request){

        if (!empty(session()->get('spin_offer')['CouponCode'])){
            return response()->json(['error'=>'You Can not buy offer product and other product same time.Please Buy offer product or delete your cart if you want to buy another product!']);
        }

//        $stock_check = Stock::where('ProductCode',$request->ProductCode)->first();
//
//        if (isset($stock_check) && !empty($stock_check)){
//
//            if ($request->quantity > $stock_check->Closing){
//                return response()->json(['error'=>'Item Quantity not Available!']);
//            }else{

                $duplicates = Cart::search(function ($cartItem, $rowId) use ($request) {
                    return $cartItem->ProductCode === $request->ProductCode;
                });

                if ($duplicates->isNotEmpty()) {
                    return response()->json(['error'=>'Item is already in your cart!']);
                }

                $product = Product::where('ProductCode',$request->ProductCode)->first();

                Cart::add($product->ProductCode, $product->ProductName, $request->quantity, $product->ItemFinalPrice,['ItemPrice' => $product->ItemPrice])
                    ->associate('App\Model\Product');

                $data = "";
                $image_url = config('app.base_image_url');

                $data = $data."<div class='flyout-cart-scroll-area'>
                                        <div class='items ps-container'>";

                foreach(Cart::content() as $item) {

                    $data = $data . "<div class='item first'>
                                <div class='picture'>
                                    <a href='".route('product.details', $item->model->ProductSlug)."'>
                                        <img alt='' src='".$image_url.'product/'.$item->model->ProductImageFileName."'>
                                    </a>
                                </div>
                                <div class='product'>
                                    <div class='left'>
                                        <div class='name'>
                                            <a href='".route('product.details', $item->model->ProductSlug)."'>".$item->name."</a>
                                        </div>
                                    </div>
                                    <div class='right'>
                                        <div class='price'> <span>".$item->price."৳</span></div>
                                        <div class='quantity'>Qty: <span>".$item->qty."</span></div>
                                    </div>
                                    <a class='remove-item' href='' title='remove'>remove</a>
                                </div>
                            </div>";
                }
                $data = $data."</div>
                                    <div class='flyout-lower'>
                                        <div class='count'>
                                            <a href='".route('cart')."'>".Cart::instance('default')->count()." item(s)</a>
                                        </div>
                                        <div class='totals'>Total: <strong>".Cart::total()."৳</strong></div>
                                        <div class='buttons'>
                                           <a href='".route('cart')."' class='button-1 cart-button' style='width: 100%;text-align: center;padding-top: 11px;color: white;font-size: 20px;'>Go to cart</a>
                                        </div>
                                    </div>";

                return response()->json([
                    'success'=>'Item Added Successfully in your cart!',
                    'qty' => Cart::instance('default')->count(),
                    'total_price' => Cart::total(),
                    'cart_data' => $data,
                ]);
//            }
//        }else{
//            return response()->json(['error'=>'Item Quantity not Available!']);
//        }
//
    }

    public function cartStoreBuyNow(Request $request){

        if (!empty(session()->get('spin_offer')['CouponCode'])) {
            return response()->json(['error'=>'You Can not buy offer product and other product same time.Please Buy offer product or delete your cart if you want to buy another product!']);
        }

        $duplicates = Cart::search(function ($cartItem, $rowId) use ($request) {
            return $cartItem->ProductCode === $request->ProductCode;
        });

        if ($duplicates->isNotEmpty()) {
            return response()->json(['error'=>'Item is already in your cart!']);
        }

        $product = Product::where('ProductCode',$request->ProductCode)->first();

        Cart::add($product->ProductCode, $product->ProductName, $request->quantity, $product->ItemFinalPrice, ['ItemPrice' => $product->ItemPrice])
            ->associate('App\Model\Product');

        $data = "";

        return response()->json([
            'success'=>'Item Added Successfully in your cart!',
            'qty' => Cart::instance('default')->count(),
            'total_price' => Cart::total(),
            'cart_data' => $data,
        ]);
    }

    public function cartStoreWithOffer(Request $request){

        $duplicates = Cart::search(function ($cartItem, $rowId) use ($request) {
            return $cartItem->ProductCode === $request->ProductCode;
        });

        if ($duplicates->isNotEmpty()) {
            return response()->json(['error'=>'Item is already in your cart!']);
        }

        $product = Product::where('ProductCode',$request->ProductCode)->first();
        $product_offer = CouponLog::where('CouponCode',$request->CouponCode)->where('UserId',Auth::user()->CustomerID)->orderBy('CouponID','desc')->first();

        if (!empty($product_offer)) {

            if ($product_offer->Used == 1) {
                return response()->json([
                    'error'=>'You Can Not Buy This Product Because You already use this Offer Code, Please Play again and Buy This product!',
                ]);
            }else {
                if (Cart::instance('default')->count() == 0){
                    Cart::add($product->ProductCode, $product->ProductName, $request->quantity, $product->ItemFinalPrice, ['ItemPrice' => $product->ItemPrice])
                        ->associate('App\Model\Product');

                    session()->put('spin_offer',[
                        'CouponCode'=>$product_offer->CouponCode,
                        'offer'=>$product_offer->Offer,
                        'ProductCode'=>$product->ProductCode,
                    ]);

                    return response()->json([
                        'success'=>'Item Added Successfully in your cart!',
                    ]);

                }else {
                    return response()->json([
                        'error'=>'You Can Buy Only One Product!',
                    ]);
                }
            }
        }
    }

    public function cartUpdate(Request $request){

//        $stock_check = Stock::where('ProductCode',$request->ProductCode)->first();
//
//        if (isset($stock_check) && !empty($stock_check)){
//
//            if ($request->quantity > $stock_check->Closing){
//                return response()->json(['error'=>'Item Quantity not Available!']);
//            }else{

                $price = $request->quantity * $request->ItemPrice;
                Cart::update($request->rowId, $request->quantity);

                return response()->json([
                    'success'=>'Item Updated Successfully in your cart!',
                    'qty' => Cart::instance('default')->count() ,
                    'price' => $price ,
                    'subtotal' => Cart::subtotal(),
                    'total_price' => Cart::total(),
                ]);
//            }
//        }else{
//            return response()->json(['error'=>'Item Quantity not Available!']);
//        }

    }

    public function cartDestroy(Request $request,$id){
        session()->forget('spin_offer');
        session()->forget('coupon_offer');
        Cart::remove($id);
        return response()->json(['success'=>'Item Deleted Successfully in your cart!','qty' => Cart::count()]);
    }

    public function cartClear(){
        session()->forget('spin_offer');
        session()->forget('coupon_offer');
        Cart::destroy();
        return response()->json(['success'=>'Cart Clear Successfully!','qty' => Cart::count()]);
    }

}
