<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Image;

class ProductModelController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $product_models = ProductModel::orderBy('id','Desc')->with('product')->paginate(20);
        return view("product_model.product_list",compact("product_models"));
    }

    public function create(){
        $products = Product::all();
        return view("product_model.product_create",compact('products'));
    }

    public function store(Request $request){
        $this->validate($request,[
            "product_id"=>"required",
            "model_name"=>"required|max:191|unique:product_model",
            "model_name_bn"=>"required|max:191|unique:product_model",
            "model_code"=>"required|max:191|unique:product_model",
        ]);

        $product_model = new ProductModel();
        $product_model->product_id = $request->product_id;
        $product_model->model_name = $request->model_name;
        $product_model->model_name_bn = $request->model_name_bn;
        $product_model->model_code = $request->model_code;
        $product_model->details = $request->details;

        if ($request->hasFile('model_image')) {
            $image       = $request->file('model_image');
            $filename    = time().".". $image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());
            $image_resize->save(public_path('/product_image/' .$filename));
            $product_model->model_image = $filename;
        }

        $product_model->save();

        Session::flash("success", "Product Model Created Successfully !");
        return redirect("/product_model");
    }

    public function edit($id){
        $product_model = ProductModel::findOrFail($id);
        $products = Product::all();
        return view("product_model.product_edit",compact("product_model","products"));;
    }

    public function update(Request $request, $id) {

        $this->validate($request,[
            "product_id"=>"required",
            "model_name"=>"required|max:191",
            "model_name_bn"=>"required|max:191",
            "model_code"=>"required|max:191",
        ]);

        $product_model = ProductModel::findOrFail($id);
        $product_model->product_id = $request->product_id;
        $product_model->model_name = $request->model_name;
        $product_model->model_name_bn = $request->model_name_bn;
        $product_model->model_code = $request->model_code;
        $product_model->details = $request->details;

        if ($request->hasFile('model_image')) {
            if(file_exists(public_path('/product_image/'.$product_model->model_image))){
                if (!empty($product_model->model_image)) {
                    unlink(public_path('/product_image/'.$product_model->model_image));
                }
            }
            $image       = $request->file('model_image');
            $filename    = time().".". $image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());
            $image_resize->save(public_path('/product_image/' .$filename));
            $product_model->model_image = $filename;
        }

        $product_model->save();

        Session::flash("success", "Edited Successfully !");
        return redirect("/product_model");
    }

    public function show($id){
        $product_model = ProductModel::find($id);
        return view("product_model.product_show",compact("product_model"));
    }

    public function destroy($id){
        $product_model = ProductModel::findOrFail($id);
        $product_model ->delete();
        Session::flash("success", "Deleted Successfully !");
        return redirect("/product_model");
    }

}
