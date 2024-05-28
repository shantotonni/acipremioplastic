<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\ServiceType;
use App\Http\Requests\ServiceTypeStoreRequest;
use App\Http\Requests\ServiceTypeUpdateRequest;

use App\Http\Resources\ServiceType as ServiceTypeResource;


class ServiceTypeController extends Controller{


    public function index(){
        //return ServiceType::all();
        return ServiceTypeResource::collection(ServiceType::all());
    }

    public function store(ServiceTypeStoreRequest $request){

        $service_type= new ServiceType;
        $service_type->name=$request->name;
        $service_type->name_bn=$request->name_bn;
        $service_type->code=$request->code;
        $service_type->save();

        return new ServiceTypeResource($service_type);
    }

    public function update(ServiceTypeUpdateRequest $request, $id) {

        $service_type=ServiceType::findOrFail($id);
        $service_type->name=$request->name;
        $service_type->name_bn=$request->name_bn;
        $service_type->code=$request->code;
        $service_type->save();

        return  new ServiceTypeResource($service_type);
    }

    public function show($id){
        return new ServiceTypeResource(ServiceType::find($id));
    }

    public function destroy($id){
        $service_type = ServiceType::findOrFail($id);
        $service_type ->delete();
        return '';
    }

}
