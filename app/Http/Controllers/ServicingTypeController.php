<?php

namespace App\Http\Controllers;

use App\ServicingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ServicingTypeController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $servicing_types = ServicingType::orderBy('id','Desc')->paginate(20);
        return view("servicing_type.servicing_type_list",compact("servicing_types"));
    }

    public function create(){
        return view("servicing_type.servicing_type_create");;
    }

    public function store(Request $request){
        $this->validate($request,[
            "name"=>"required|max:191|unique:servicing_types",
            "code"=>"required|max:191|unique:servicing_types",
        ]);

        $servicing_type = ServicingType::create($request->all());
        Session::flash("success", "Created Successfully !");
        return redirect("/servicing_type");
    }

    public function edit($id){
        $servicing_type = ServicingType::findOrFail($id);
        return view("servicing_type.servicing_type_edit",compact("servicing_type"));;
    }

    public function update(Request $request, $id) {
        $this->validate($request,[
            "name"=>"required|max:191|unique:servicing_types",
            "code"=>"required|max:191|unique:servicing_types",
        ]);

        $servicing_type = ServicingType::find($id)->update($request->all());
        Session::flash("success", "Edited Successfully !");
        return redirect("/servicing_type");
    }

    public function show($id){
        $servicing_type = ServicingType::find($id);
        return view("servicing_type.servicing_type_show",compact("servicing_type"));
    }

    public function destroy($id){
        $servicing_type = ServicingType::findOrFail($id);
        $servicing_type ->delete();
        Session::flash("success", "Deleted Successfully !");
        return redirect("/servicing_type");
    }
}
