<?php

namespace App\Http\Controllers;

use App\TractorProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Image;

class TractorProductController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $products = TractorProduct::orderBy('id','Desc')->paginate(20);
        return view("tractor_product.product_list",compact("products"));
    }

    public function create(){

        return view("tractor_product.product_create");;
    }

    public function store(Request $request){

        $this->validate($request,[
            "name"=>"required|max:191|unique:tractor_products",
            "name_bn"=>"required|max:191|unique:tractor_products",
            "code"=>"required|max:191|unique:tractor_products",
        ]);

        $product = new TractorProduct();
        $product->name = $request->name;
        $product->name_bn = $request->name_bn;
        $product->code = $request->code;
        $product->details = $request->details;

        if ($request->hasFile('product_image')) {
            $image       = $request->file('product_image');
            $filename    = time().".". $image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());
            $image_resize->save(public_path('/product_image/' .$filename));
            $product->product_image = $filename;
        }

        $product->save();

        Session::flash("success", "Product Created Successfully !");
        return redirect("/tractor-product");
    }


    public function edit($id){

        $product = TractorProduct::findOrFail($id);
        return view("tractor_product.product_edit",compact("product"));;

    }

    public function update(Request $request, $id) {

        $this->validate($request,[
            "name"=>"required|max:191|unique:tractor_products,name,".$id,
            "name_bn"=>"required|max:191|unique:tractor_products,name_bn,".$id,
            "code"=>"required|max:191|unique:tractor_products,code,".$id,
        ]);

        $product = TractorProduct::findOrFail($id);
        $product->name = $request->name;
        $product->name_bn = $request->name_bn;
        $product->code = $request->code;
        $product->details = $request->details;

        if ($request->hasFile('product_image')) {
            if(file_exists(public_path('/product_image/'.$product->product_image))){
                if (!empty($product->product_image)) {
                    unlink(public_path('/product_image/'.$product->product_image));
                }
            }
            $image       = $request->file('product_image');
            $filename    = time().".". $image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());
            $image_resize->save(public_path('/product_image/' .$filename));
            $product->product_image = $filename;
        }

        $product->save();

        Session::flash("success", "Edited Successfully !");
        return redirect("/tractor-product");
    }

    public function show($id){
        $product = TractorProduct::find($id);
        return view("tractor_product.product_show",compact("product"));
    }


    public function destroy($id){
        $product = TractorProduct::findOrFail($id);
        $product ->delete();
        Session::flash("success", "Deleted Successfully !");
        return redirect("/tractor-product");
    }

}
