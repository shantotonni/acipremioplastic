<?php

namespace App\Http\Controllers;

use App\Model\Category;
use App\Model\Coupon;
use App\Model\Offer;
use App\Model\OfferProduct;
use App\Model\Product;
use App\Model\Slider;
use App\Model\SubCategory;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use MongoDB\Driver\Session;

class HomeController extends Controller
{
    public function index()
    {
        $project_id = config('app.project_id');
        $sliders = Slider::select('BannerID','BannerImageFile','Url')->where('Active','Y')->where('ProjectID',$project_id)->get();
        return view('home',compact('sliders'));
    }

    public function category($slug){
        $project_id = config('app.project_id');
        $category = Category::where('CategorySlug',$slug)->where('CategoryStatus','Y')->where('ProjectID',$project_id)->with('subcategory','products')->first();
        if (isset($category) && !empty($category)){
            if (count($category->subcategory) > 0){
                return view('sub_category',compact('category'));
            }else{
                return view('category_product',compact('category'));
            }
        }else{
            $sub_category = SubCategory::where('SubcategorySlug',$slug)->where('ProjectID',$project_id)->where('SubCategoryStatus','Y')->with('products')->first();
            return view('category_product',compact('sub_category'));
        }

    }

    public function categoryProduct($slug){
        $project_id = config('app.project_id');
        $category = Category::where('CategorySlug',$slug)->where('ProjectID',$project_id)->where('CategoryStatus','Y')->with('products')->first();
        return view('category_product',compact('category'));

    }

    public function productDetails($slug){
        $project_id = config('app.project_id');
        $product = Product::where('ProductSlug',$slug)->where('ProjectID',$project_id)->with('productImage','review','average','category')->where('ProductStatus','Y')->first();

        if (isset($product) && !empty($product)){
            $products = Product::where('CategoryId',$product->CategoryId)->where('ProjectID',$project_id)->where('ProductStatus','Y')->inRandomOrder()->take(12)->get();
            return view('product_details',compact('product','products'));
        }else{
            Toastr::error('Something went wrong' ,'Success');
            return redirect()->back();
        }

    }

    public function search(Request $request){

        $project_id = config('app.project_id');

        $query = $request->q;
        $result = Product::where('ProductName', 'LIKE', "%{$query}%")
            ->select('ProductCode','ProjectID','ProductImageFileName','ProductName','ItemFinalPrice','ProductSlug')
            ->where('ProjectID',$project_id)
            ->where('ProductStatus','Y')
            ->get();
        $image_url = config('app.base_image_url');
        $data = "";

        foreach ($result as $value){
            $data = $data."<a class='link-p-colr' href='".route('product.details',$value->ProductSlug)."'>
                                <div class='live-outer'>
                                    <div class='live-im'>
                                        <img src='".$image_url.'product/'.$value->ProductImageFileName."'/>
                                    </div>
                                    <div class='live-product-det'>
                                        <div class='live-product-name'>
                                            <p>".$value->ProductName."</p>
                                        </div>
                                        <div class='live-product-price'>
                                            <div class='live-product-price-text'><p>TK.".$value->ItemFinalPrice."</p></div>
                                        </div>
                                    </div>
                                </div>
	                        </a>
	                    ";
                     }
//echo $data;
        return response()->json($data);
    }

    public function newArrivals(){
        $project_id = config('app.project_id');
        $products = Product::where('ProjectID',$project_id)->orderBy('CreatedDate','desc')->where('ProductStatus','Y')->take(20)->get();
        return view('new_arrivals',compact('products'));
    }

    public function currentOffer(){
        $project_id = config('app.project_id');
        $products = Product::where('ProjectID',$project_id)
            ->where('ProductStatus','Y')
            ->whereNotNull('Discount')
            ->where('Discount','!=',0)
            ->orderBy('CreatedDate','desc')
            ->paginate(20);
        return view('current_offer',compact('products'));
    }

    public function getOffers(){
        $colors = ['#eae56f','#89f26e','#7de6ef','#4fa978','#4fa978'];

        $project_id = config('app.project_id');
        $coupons = Coupon::where('ProjectId',$project_id)->where('Status','active')->orderBy('CreatedAt','desc')->get();
        $pages_array = [];
        foreach ($coupons as $key => $coupon){
            $pages_array[] =[
                'fillStyle' => $colors[$key] ?? '#89f26e',
                'text' => $coupon->CouponName,
                'strokeStyle' => 'white',
                'couponCode' => $coupon->CouponCode
            ];
        }

        return view('get_offers',compact('pages_array'));
    }

    public function claimOffers(Request $request){
        $request->session()->put('offer', $request->offer);
        $request->session()->put('mobile', $request->moblie);
        return view('claim_offer');
    }

    public function offerCategory()
    {
        $image_url = config('app.base_image_url');
        $offers = Offer::where('Active','Y')->get();
        return view('offer_category',compact('offers','image_url'));
    }

    public function offerDetails($ID)
    {
        $image_url = config('app.base_image_url');
        $offer = Offer::where('ID',$ID)->with('offer_products.products')->first();
        return view('offer_category_product',compact('offer','image_url'));
    }
    public function productOfferDetails($id){
        $project_id = config('app.project_id');
        $product = Product::where('ProductCode',$id)->where('ProjectID',$project_id)->with('productImage','review','average','category')->where('ProductStatus','Y')->first();

        if (isset($product) && !empty($product)){
            $products = Product::where('CategoryId',$product->CategoryId)->where('ProjectID',$project_id)->where('ProductStatus','Y')->inRandomOrder()->take(12)->get();
            return view('product_details',compact('product','products'));
        }else{
            Toastr::error('Something went wrong' ,'Success');
            return redirect()->back();
        }

    }

}
