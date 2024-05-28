<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Feature;
use Session;

class FeaturesController extends Controller
{
    

     public function __construct(){
        $this->middleware('auth');
        
        
    }

    public function index(){   
        if(!AdminController::isAccessable(1)) return redirect('/home')->with('danger',"No Permission");

        $features = Feature::paginate(20);
        return view('feature.features',compact('features'));
    }

   
    public function create(){
     // if(!AdminController::isAccessable(1)) return redirect('/home')->with('danger',"No Permission");
      return view('feature.features_create');
    }

   
    public function store(Request $request){
         // if(!AdminController::isAccessable(1)) return redirect('/home')->with('danger',"No Permission");  

          $this->validate($request, [
            'name' => 'required|unique:features',
            'code' => 'required|unique:features',
            ]);
          $feature = new Feature;
          $feature->name=$request->name;
          $feature->code=$request->code;
          $feature->save(); 
          Session::flash('success','Successfully Created');  
          return redirect('/feature');

    }

    
    public function show($id)
    {
   
       
    }

    
    public function edit($id) {
      if(!AdminController::isAccessable(1)) return redirect('/home')->with('danger',"No Permission");  
     $feature = Feature::find($id);
     return view('feature.features_edit',compact('feature'));  
    }

    
    public function update(Request $request, $id)
    {
         if(!AdminController::isAccessable(1)) return redirect('/home')->with('danger',"No Permission");  
    
          $this->validate($request, [
            'name' => 'required|unique:features',
            'code' => 'required|unique:features',
            ]);
          $feature = Feature::find($id);
          $feature->name=$request->name;
          $feature->code=$request->code;
          $feature->save(); 
          Session::flash('success','Successfully Edited');  
          return redirect('/feature');
    }

    
    public function destroy($id)
    {
        //
    }
}
