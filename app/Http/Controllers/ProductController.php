<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Product;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use Image;


class ProductController extends Controller{

    public function __construct(){
       $this->middleware('auth');
    }

    public function index(){
        $products = Product::orderBy('id','Desc')->paginate(20);
        return view("product.product_list",compact("products"));
    }

    public function create(){
        
        return view("product.product_create");;
    }

    public function store(ProductStoreRequest $request){

        $product = new Product;
        $product->name = $request->name;
        $product->name_bn = $request->name_bn;
        $product->code = $request->code;
        $product->type = $request->type;
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
        return redirect("/product");
    }


    public function edit($id){
        
        $product = Product::findOrFail($id);
        return view("product.product_edit",compact("product"));;

    }

    public function update(ProductUpdateRequest $request, $id) {

        $product = Product::findOrFail($id);
        $product->name=$request->name;
        $product->name_bn=$request->name_bn;
        $product->code=$request->code;
        $product->type = $request->type;
        $product->details=$request->details;

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
        return redirect("/product");
    }

    public function show($id){
        $product = Product::find($id);
        return view("product.product_show",compact("product"));
    }


    public function destroy($id){
        $product = Product::findOrFail($id);
        $product ->delete();
        Session::flash("success", "Deleted Successfully !");
        return redirect("/product");
    }

}
