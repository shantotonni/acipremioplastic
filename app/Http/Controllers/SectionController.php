<?php

namespace App\Http\Controllers;

use App\Product;
use App\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SectionController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $sections = Section::orderBy('created_at','desc')->with('product')->paginate(10);
        return view('section.list',compact('sections'));
    }

    public function create()
    {
        $products = Product::all();
        return view('section.create',compact('products'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
           'name' => 'required',
           'code' => 'required',
           'product_id' => 'required'
        ]);

        $section = new Section();
        $section->name = $request->name;
        $section->code = $request->code;
        $section->product_id = $request->product_id;
        $section->save();

        Session::flash("success", "Section Created Successfully !");
        return redirect()->route('sections.index');
    }

    public function show(Section $section)
    {
        //
    }

    public function edit(Section $section)
    {
        $products = Product::all();
        return view('section.edit',compact('section','products'));
    }

    public function update(Request $request, Section $section)
    {
        $this->validate($request,[
            'name' => 'required',
            'product_id' => 'required',
            'code' => 'required'
        ]);

        $section->name = $request->name;
        $section->code = $request->code;
        $section->product_id = $request->product_id;
        $section->save();

        Session::flash("success", "Section Updated Successfully !");
        return redirect()->route('sections.index');
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return response()->json(['msg' => 'Data deleted successfully'], 200);
    }
}
