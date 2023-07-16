<?php

namespace App\Http\Controllers;

use App\Model\Product;
use App\Model\WishList;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except' => array('wishlistStore')]);
    }

    public function wishlist(){
        $wishlists = WishList::where('CustomerID',Auth::user()->CustomerID)->with('user','product')->get();
        return view('wishlist',compact('wishlists'));
    }

    public function wishlistStore(Request $request){

        if (Auth::check()){
            $project_id = config('app.project_id');
            $ip = $_SERVER['REMOTE_ADDR'];

            $exists = WishList::where('ProductCode',$request->ProductCode)->exists();

            if ($exists){
                return response()->json(['error'=>'Already added in Your Wishlist!']);
            }

            $wishlist = new WishList();
            $wishlist->ProjectID = $project_id;
            $wishlist->CustomerID = Auth::user()->CustomerID;
            $wishlist->ProductCode = $request->ProductCode;
            $wishlist->CreatedDate = Carbon::now();
            $wishlist->EditedIP = $ip;
            $wishlist->EditedDeviceState = 'dhaka';

            if ($wishlist->save()){
                return response()->json([
                    'success'=>'Item Added Successfully in your Wish List!',
                    'qty'  =>count(WishList::all())
                ]);
            }else{
                return response()->json(['error'=>'Something Went Wrong!']);
            }

        }else{
            return response()->json(['error'=>'Please Login First!']);
        }

    }

    public function wishlistToCart(Request $request){

        $wishlist = WishList::where('ProductCode',$request->ProductCode)->first();

        $duplicates = Cart::search(function ($cartItem, $rowId) use ($request) {
            return $cartItem->ProductCode === $request->ProductCode;
        });

        if ($duplicates->isNotEmpty()) {
            return response()->json(['error'=>'Item is already in your cart!']);
        }

        Cart::add($wishlist->product->ProductCode, $wishlist->product->ProductName, 1, $wishlist->product->ItemFinalPrice)
            ->associate('App\Model\Product');

        $wishlist->delete();

        return response()->json([
            'success'=>'Item Added Successfully in your cart!',
        ]);
    }


    public function customerDestroy(Request $request,$id){
        $wishlist = WishList::where('WishListID',$id)->first();
        $wishlist->delete();
        return response()->json(['success'=>'Item Deleted Successfully in your cart!']);
    }


}
