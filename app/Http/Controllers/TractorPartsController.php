<?php

namespace App\Http\Controllers;

use App\ProductModel;
use App\Section;
use App\TractorParts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Image;

class TractorPartsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        //$tractor_parts = TractorParts::orderBy('id','Desc')->get();
        $tractor_parts = DB::select("
                                SELECT T.id,P.ProductCode,T.custom_name,T.image,PM.model_name_bn,S.name as section_name, FORMAT(P.UnitPrice, 'N2') as UnitPrice FROM tractor_parts as T
                                inner join [192.168.100.25].MotorSparePartsMirror.dbo.Product as P on P.ProductCode = T.ProductCode
                                inner join product_model as PM on PM.id = T.product_model_id
                                inner join sections as S on S.id = T.section_id
                                WHERE P.Business='Q'
                                ");
       // return $tractor_parts;
        return view("tractor_part.list",compact("tractor_parts"));
    }

    public function create(){
        $sections = Section::all();
        //$products = DB::connection('ECommerce')->select("SELECT ProductCode,ProductName,FORMAT(ItemFinalPrice, 'N2') as UnitPrice FROM Product WHERE ProjectID='2'");
        $products = DB::connection('MotorSparePartsMirror')->select("SELECT ProductCode,ProductName,FORMAT(UnitPrice, 'N2') as UnitPrice FROM Product WHERE Business='Q'");
        $productModels = ProductModel::where('product_id', 1)->get();
        return view("tractor_part.create")
            ->with("sections" ,$sections)
            ->with("products" ,$products)
            ->with("productModels" ,$productModels);
    }

    public function store(Request $request){
         $this->validate($request,[
             "code"=>"required|max:191",
         ]);

        $tractor_part= new TractorParts();
        $tractor_part->ProductCode = $request->code;
        $tractor_part->custom_name = $request->custom_name;
        $tractor_part->section_id = $request->section_id;
        $tractor_part->product_model_id = $request->product_model_id;

       if ($request->hasFile('image')) {
           $image       = $request->file('image');
           $filename    = time().".". $image->getClientOriginalExtension();
           $image_resize = Image::make($image->getRealPath());
           $image_resize->save(public_path('/part_image/' .$filename));
           $tractor_part->image = $filename;
       }

        $tractor_part->save();

        Session::flash("success", "Parts Created Successfully !");
        return redirect()->route('tractor_part.index');
    }

    public function edit($id){

        $sections = Section::all();
        $tractor_part = TractorParts::findOrFail($id);
        $products = DB::connection('MotorSparePartsMirror')->select("SELECT ProductCode,ProductName,FORMAT(UnitPrice, 'N2') as UnitPrice FROM Product WHERE Business='Q'");
        $productModels = ProductModel::where('product_id', 1)->get();
        return view("tractor_part.edit",compact("tractor_part"))
            ->with("sections" ,$sections)
            ->with("products" ,$products)
            ->with("productModels" ,$productModels);
    }

    public function update(Request $request, $id) {
         $this->validate($request,[
             "code"=>"required|max:191",
         ]);

        $tractor_part=TractorParts::findOrFail($id);
        $tractor_part->ProductCode = $request->code;
        $tractor_part->custom_name = $request->custom_name;
        $tractor_part->section_id = $request->section_id;
        $tractor_part->product_model_id = $request->product_model_id;

       if ($request->hasFile('image')) {
           if(file_exists(public_path('/part_image/'.$tractor_part->image)))
               unlink(public_path('/part_image/'.$tractor_part->image));
           $image       = $request->file('image');
           $filename    = time().".". $image->getClientOriginalExtension();
           $image_resize = Image::make($image->getRealPath());
           $image_resize->save(public_path('/part_image/' .$filename));
           $tractor_part->image=$filename;
       }

        $tractor_part->save();

        Session::flash("success", "Parts Edited Successfully !");
        return redirect()->route('tractor_part.index');
    }

    public function show($id){
        $tractor_part = TractorParts::find($id);
        return view("tractor_part.show",compact("tractor_part"));
    }

    public function destroy($id){
        $tractor_part = TractorParts::findOrFail($id);
        $tractor_part ->delete();
        Session::flash("success", "Deleted Successfully !");
        return response()->json(['msg' => 'Data deleted successfully'], 200);
    }
}
