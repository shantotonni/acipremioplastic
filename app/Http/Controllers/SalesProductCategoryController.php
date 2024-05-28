<?php

namespace App\Http\Controllers;

use App\SalesProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SalesProductCategoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
     }
  
     public function index(){
         $categories = SalesProductCategory::orderBy('id','Desc')->get();
         return view("sales_product_category.list",compact("categories"));
     }
  
     public function create(){
         
         return view("sales_product_category.create");;
     }
 
     public function store(Request $request){
        
        $fileName='';
        if ($request->hasFile('image')) {
            $fileName = 'sales_category_'.time().'.'.$request->image->extension();  
    
            $request->image->move(public_path('uploads'), $fileName);
        }
         $category= new SalesProductCategory;
         $category->name=$request->name;
         $category->image=$fileName;
         $category->save();
 
         Session::flash("success", "Created Succcessfully !");
         return redirect(route('sales-product-category.index'));
     }
 
     public function edit($id){
         
         $category = SalesProductCategory::findOrFail($id);
         return view("sales_product_category.edit",compact("category"));;
 
     }
 
     public function update(Request $request, $id) {
        
        $fileName='';
        if ($request->hasFile('image')) {
            $fileName = 'sales_category_'.time().'.'.$request->image->extension();  
    
            $request->image->move(public_path('uploads'), $fileName);
        }

         $category=SalesProductCategory::findOrFail($id);
         $category->name=$request->name;
         $category->image=$fileName;
         $category->save();
 
         Session::flash("success", "Edited Succcessfully !");
         return redirect(route('sales-product-category.index'));
     }
 
     public function show($id){
         $category = SalesProductCategory::find($id);
         return view("sales_product_category.show",compact("category"));
     }
 
 
     public function destroy($id){
         $category = SalesProductCategory::findOrFail($id);
         $category ->delete();
         Session::flash("success", "Deleted Succcessfully !");
         return redirect(route('sales-product-category.index'));
     }
}
