<?php

namespace App\Http\Controllers;

use App\SalesProduct;
use App\SalesProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SalesProductsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
     }
  
     public function index(){
         $products = SalesProduct::orderBy('id','Desc')->get();
         return view("sales_product.list",compact("products"));
     }
  
     public function create(){
         $categories = SalesProductCategory::get();

         return view("sales_product.create", compact('categories'));;
     }
 
     public function store(Request $request){
        
        $fileName='';
        if ($request->hasFile('image')) {
            $fileName = 'sales_product_'.time().'.'.$request->image->extension();  
    
            $request->image->move(public_path('uploads'), $fileName);
        }
         $product= new SalesProduct;
         $product->name=$request->name;
         $product->sales_product_category_id=$request->sales_product_category_id;
         $product->image=$fileName;
         $product->detail=$request->detail;
         $product->save();
 
         Session::flash("success", "Created Succcessfully !");
         return redirect(route('sales-products.index'));
     }
 
     public function edit($id){

         $categories = SalesProductCategory::get();
         $product = SalesProduct::findOrFail($id);

         return view("sales_product.edit",compact("categories",'product'));;
 
     }
 
     public function update(Request $request, $id) {
        
        $fileName='';
        if ($request->hasFile('image')) {
            $fileName = 'sales_product_'.time().'.'.$request->image->extension();  
    
            $request->image->move(public_path('uploads'), $fileName);
        }

         $product=SalesProduct::findOrFail($id);
         $product->name=$request->name;
         $product->sales_product_category_id=$request->sales_product_category_id;
         if($fileName!=''){
            $product->image=$fileName;
         }
         $product->detail=$request->detail;
         $product->save();
 
         Session::flash("success", "Edited Succcessfully !");
         return redirect(route('sales-products.index'));
     }
 
     public function show($id){
         $product = SalesProduct::find($id);
         return view("sales_product.show",compact("product"));
     }
 
 
     public function destroy($id){
         $product = SalesProduct::findOrFail($id);
         $product ->delete();
         Session::flash("success", "Deleted Succcessfully !");
         return redirect(route('sales-products.index'));
     }
}
