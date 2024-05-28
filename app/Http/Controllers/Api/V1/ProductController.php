<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Product;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\Product as ProductResource;

class ProductController extends Controller{


    public function __construct(){
      // $this->middleware('auth');
    }

    public function index(){
        //return Product::all();
        return ProductResource::collection(Product::all());
    }

    public function store(ProductStoreRequest $request){

        $product= new Product;
        $product->name=$request->name;
        $product->name_bn=$request->name_bn;
        $product->code=$request->code;
        $product->save();

        return new ProductResource($product);
    }

    public function update(ProductUpdateRequest $request, $id) {

        $product=Product::findOrFail($id);
        $product->name=$request->name;
        $product->name_bn=$request->name_bn;
        $product->code=$request->code;
        $product->save();

        return  new ProductResource($product);
    }

    public function show($id){
        return new ProductResource(Product::find($id));
    }

    public function destroy($id){
        $product = Product::findOrFail($id);
        $product ->delete();
        return '';
    }

}
