<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\CallType;
use App\Http\Requests\CallTypeStoreRequest;
use App\Http\Requests\CallTypeUpdateRequest;

use App\Http\Resources\CallType as CallTypeResource;


class CallTypeController extends Controller{


    public function __construct(){
      // $this->middleware('auth');
    }


    public function index(){
        //return CallType::all();
        return CallTypeResource::collection(CallType::all());
    }


    public function store(CallTypeStoreRequest $request){

        $call_type= new CallType;
        $call_type->name=$request->name;
        $call_type->name_bn=$request->name_bn;
        $call_type->code=$request->code;
        $call_type->save();

        return new CallTypeResource($call_type);
    }




    public function update(CallTypeUpdateRequest $request, $id) {

        $call_type=CallType::findOrFail($id);
        $call_type->name=$request->name;
        $call_type->name_bn=$request->name_bn;
        $call_type->code=$request->code;
        $call_type->save();

        return  new CallTypeResource($call_type);
    }

    public function show($id){
        return new CallTypeResource(CallType::find($id));
    }


    public function destroy($id){
        $call_type = CallType::findOrFail($id);
        $call_type ->delete();
        return '';
    }

}
