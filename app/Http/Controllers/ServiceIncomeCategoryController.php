<?php

namespace App\Http\Controllers;

use App\Product;
use App\ServiceIncomeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ServiceIncomeCategoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $service_category = ServiceIncomeCategory::orderBy('id','Desc')->paginate(20);
        return view("service_income_category.list",compact("service_category"));
    }

    public function create(){
        $products = Product::all();
        return view("service_income_category.create",compact('products'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'amount'=>'required',
            'product_id'=>'required',
        ]);
        $category =new ServiceIncomeCategory();
        $category->name = $request->name;
        $category->name_bn = $request->name_bn;
        $category->amount = $request->amount;
        $category->product_id = $request->product_id;
        $category->save();
        Session::flash("success", "Created Successfully !");
        return redirect("/service-income-category");
    }

    public function edit($id){
        $products = Product::all();
        $category = ServiceIncomeCategory::findOrFail($id);
        return view("service_income_category.edit",compact("category",'products'));;
    }

    public function update(Request $request, $id) {
        $this->validate($request,[
            'name'=>'required',
            'amount'=>'required',
            'product_id'=>'required',
        ]);

        $category = ServiceIncomeCategory::find($id);
        $category->name =$request->name;
        $category->name_bn =$request->name_bn;
        $category->amount =$request->amount;
        $category->product_id =$request->product_id;
        $category->save();

        Session::flash("success", "Edited Successfully !");
        return redirect("/service-income-category");
    }

    public function destroy($id){
        $category = ServiceIncomeCategory::findOrFail($id);
        $category ->delete();
        Session::flash("success", "Deleted Successfully !");
        return redirect("/service-income-category");
    }

}
