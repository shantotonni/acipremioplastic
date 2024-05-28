<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\CallType;
use App\Http\Requests\CallTypeStoreRequest;
use App\Http\Requests\CallTypeUpdateRequest;


class CallTypeController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $call_types = CallType::orderBy('id','Desc')->paginate(20);
        return view("call_type.call_type_list",compact("call_types"));
    }


    public function create(){
        
        return view("call_type.call_type_create");;
    }


    public function store(CallTypeStoreRequest $request){

        $call_type= new CallType;
        $call_type->name=$request->name;
        $call_type->name_bn=$request->name_bn;
        $call_type->code=$request->code;
        $call_type->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/call_type");
    }



    public function edit($id){
        
        $call_type = CallType::findOrFail($id);
        return view("call_type.call_type_edit",compact("call_type"));;

    }

    public function update(CallTypeUpdateRequest $request, $id) {

        $call_type=CallType::findOrFail($id);
        $call_type->name=$request->name;
        $call_type->name_bn=$request->name_bn;
        $call_type->code=$request->code;
        $call_type->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/call_type");
    }

    public function show($id){
        $call_type = CallType::find($id);
        return view("call_type.call_type_show",compact("call_type"));
    }


    public function destroy($id){
        $call_type = CallType::findOrFail($id);
        $call_type ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/call_type");
    }

}
